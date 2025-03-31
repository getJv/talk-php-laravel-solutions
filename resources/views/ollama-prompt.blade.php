<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>


    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <h1>Stream RealTime Stream with Ollama-server</h1>


    <div style=" max-width: 500px;  margin: 0 auto;">
        <label for="question" style="display: block; margin-bottom: 6px; font-weight: 500;">Digite sua pergunta:</label>
        <input
            type="text"
            id="question"
            placeholder="Ex: What is the capital of France..."
            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 16px; outline: none; transition: border-color 0.2s ease-in-out; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
            onFocus="this.style.borderColor='#3b82f6';"
            onBlur="this.style.borderColor='#d1d5db';"
        />
        <small style="display: block; margin-top: 6px; margin-bottom: 6px;  color: #6b7280; font-size: 12px;">
            Tye the question and press enter to send it to the server.
        </small>


        <button id="startStream" >Ask the question</button>
        <button id="stopStream" disabled>Stop Stream</button>

        <div id="result" style="min-height: 200px; padding: 16px; margin-top: 16px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #f9fafb; font-family: monospace; white-space: pre-wrap; overflow-y: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
        ></div>

    </div>


    <script>
        let eventSource;
        const resultDiv = document.getElementById('result');
        const startButton = document.getElementById('startStream');
        const stopButton = document.getElementById('stopStream');

        startButton.addEventListener('click', function() {

            if (eventSource) {
                eventSource.close();
            }

            resultDiv.textContent = '';
            const question = document.getElementById('question').value.trim();
            const encodedQuestion = encodeURIComponent(question);

            eventSource = new EventSource(`/stream/?q=${encodedQuestion}`);

            startButton.disabled = true;
            stopButton.disabled = false;


            eventSource.onmessage = function(event) {
                console.log('Received message:', event.data);
                try {
                    const data = JSON.parse(event.data);
                    if (data.done) {

                        eventSource.close();
                        startButton.disabled = false;
                        stopButton.disabled = true;
                        return;
                    }

                    if (data.message) {
                        resultDiv.textContent += data.message.content || '';
                    }
                } catch (e) {

                    resultDiv.textContent += event.data;
                }
            };


            eventSource.onerror = function(error) {
                console.error('EventSource error:', error);
                eventSource.close();
                startButton.disabled = false;
                stopButton.disabled = true;
            };
        });

        stopButton.addEventListener('click', function() {
            if (eventSource) {
                eventSource.close();
                startButton.disabled = false;
                stopButton.disabled = true;
            }
        });
    </script>
    </body>

</html>
