<?php

namespace App\Solutions;

use Illuminate\Foundation\Exceptions\Renderer\Frame;
use OpenAI;
use Pest\Support\Backtrace;
use Psr\SimpleCache\CacheInterface;
use Spatie\ErrorSolutions\Contracts\Solution;
use Spatie\ErrorSolutions\Solutions\OpenAi\OpenAiPromptViewModel;
use Spatie\ErrorSolutions\Solutions\OpenAi\OpenAiSolutionResponse;
use Spatie\ErrorSolutions\Support\AiPromptRenderer;
use Throwable;

class MySolutionWithAi implements Solution
{
    public bool $aiGenerated = true;

    protected string $prompt;

    protected OpenAiSolutionResponse $openAiSolutionResponse;

    public function __construct(
        protected Throwable           $throwable,
        protected string              $openAiKey,
        protected CacheInterface|null $cache = null,
        protected int|null            $cacheTtlInSeconds = 60,
        protected string|null         $applicationType = null,
        protected string|null         $applicationPath = null,
        protected string|null         $openAiModel = null,
    ) {

        $this->cache = cache()->store(config('cache.default'));

        $this->prompt = $this->generatePrompt();

        $this->openAiSolutionResponse = $this->getAiSolution();
    }

    public function getSolutionTitle(): string
    {
        return 'AI Generated Solution';
    }

    public function getSolutionDescription(): string
    {
        return $this->openAiSolutionResponse->description();
    }

    public function getDocumentationLinks(): array
    {
        return $this->openAiSolutionResponse->links();
    }

    public function getAiSolution(): ?OpenAiSolutionResponse
    {
        $solution = $this->cache->get($this->getCacheKey());

        if ($solution) {
            return new OpenAiSolutionResponse($solution);
        }

        $solutionText = OpenAI::client($this->openAiKey)
            ->chat()
            ->create([
                'model' => $this->getModel(),
                'messages' => [['role' => 'user', 'content' => $this->prompt]],
                'max_tokens' => 1000,
                'temperature' => 0,
            ])->choices[0]->message->content;

        $this->cache->set($this->getCacheKey(), $solutionText, $this->cacheTtlInSeconds);

        return new OpenAiSolutionResponse($solutionText);
    }

    protected function getCacheKey(): string
    {
        $hash = sha1($this->prompt);

        return "ignition-solution-{$hash}";
    }

    protected function generatePrompt(): string
    {
        $viewPath = __DIR__.'/../../resources/views/aiPrompt.php';

        $viewModel = new OpenAiPromptViewModel(
            file: $this->throwable->getFile(),
            exceptionMessage: $this->throwable->getMessage(),
            exceptionClass: get_class($this->throwable),
            snippet: "fake-snippet", //$this->getApplicationFrame($this->throwable)->getSnippetAsString(15),
            line: $this->throwable->getLine(),
            applicationType: $this->applicationType,
        );

        return (new AiPromptRenderer())->renderAsString(
            ['viewModel' => $viewModel],
            $viewPath,
        );
    }

    protected function getModel(): string
    {
        return $this->openAiModel ?? 'gpt-3.5-turbo';
    }

    protected function getApplicationFrame(Throwable $throwable): ?Frame
    {
        // TODO: What are these libs... frame Backtrace
        $backtrace = Backtrace::createForThrowable($throwable);

        if ($this->applicationPath) {
            $backtrace->applicationPath($this->applicationPath);
        }

        $frames = $backtrace->frames();

        return $frames[$backtrace->firstApplicationFrameIndex()] ?? null;
    }

}
