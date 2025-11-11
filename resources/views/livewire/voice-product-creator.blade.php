<div><div>

    {{-- Modal de Creación por Voz con MediaRecorder + Whisper --}}    {{-- Modal de Creación por Voz (Componente Independiente) --}}



    <div     {{-- CRITICAL: Store recognition OUTSIDE Alpine.js scope to avoid Proxy wrapping --}}

        x-data="{    <script>

            open: @entangle('showModal'),        window._voiceRecognition = null;

            recording: false,    </script>

            mediaRecorder: null,

            audioChunks: [],    <div 

            transcript: @entangle('voiceTranscript').live,        x-data="{

            processing: @entangle('voiceProcessing').live,            open: @entangle('showModal'),

            extractedData: @entangle('voiceExtractedData').live,            recording: false,

            transcript: @entangle('voiceTranscript').live,

            init() {            processing: @entangle('voiceProcessing').live,

                console.log('🎤 VoiceProductCreator init - MediaRecorder + Whisper mode');            extractedData: @entangle('voiceExtractedData').live,

            },

            init() {

            async startRecording() {                console.log('VoiceProductCreator Alpine init - Modal should be closed');

                console.log('🎤 User clicked record - requesting microphone...');                console.log('Recognition stored globally:', window._voiceRecognition);

                            },

                try {

                    // Request microphone access (easier to get than SpeechRecognition)            startRecording() {

                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });                console.log('🎤 User clicked record button');

                    console.log('✅ Microphone access granted');                console.log('Protocol:', window.location.protocol, '| HTTPS:', window.location.protocol === 'https:');

                                    

                    this.audioChunks = [];                // Use GLOBAL variable to avoid Alpine.js Proxy wrapping the native API

                    this.mediaRecorder = new MediaRecorder(stream);                if (!window._voiceRecognition) {

                                        if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {

                    this.mediaRecorder.ondataavailable = (event) => {                        console.error('❌ Speech Recognition NOT supported');

                        if (event.data.size > 0) {                        Swal.fire('No Soportado', 'Tu navegador no soporta reconocimiento de voz.', 'error');

                            this.audioChunks.push(event.data);                        return;

                            console.log('📦 Audio chunk collected:', event.data.size, 'bytes');                    }

                        }

                    };                    console.log('✅ Creating Speech Recognition instance...');

                                        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;

                    this.mediaRecorder.onstop = async () => {                    window._voiceRecognition = new SR();

                        console.log('⏹️ Recording stopped, processing', this.audioChunks.length, 'chunks...');                    window._voiceRecognition.lang = 'es-CO';

                                            window._voiceRecognition.continuous = false;

                        // Create audio blob                    window._voiceRecognition.interimResults = false;

                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });

                        console.log('📦 Audio blob created:', audioBlob.size, 'bytes');                    console.log('✅ Recognition object created (stored globally)');

                        

                        // Convert to base64                    window._voiceRecognition.onstart = () => {

                        const reader = new FileReader();                        console.log('✅✅✅ Recognition STARTED successfully!');

                        reader.readAsDataURL(audioBlob);                    };

                        reader.onloadend = async () => {

                            const base64Audio = reader.result.split(',')[1];                    window._voiceRecognition.onresult = (event) => {

                            console.log('🔄 Sending audio to backend (Whisper API)...');                        console.log('✅ Recognition RESULT:', event.results[0][0].transcript);

                                                    this.transcript = event.results[0][0].transcript;

                            // Send to Livewire backend                        this.recording = false;

                            await $wire.transcribeAudio(base64Audio);                    };

                        };

                                            window._voiceRecognition.onerror = (event) => {

                        // Stop all tracks                        console.error('❌ Speech error:', event.error, event);

                        stream.getTracks().forEach(track => track.stop());                        this.recording = false;

                    };                        

                                            if (event.error === 'not-allowed') {

                    this.mediaRecorder.start();                            Swal.fire({

                    this.recording = true;                                title: 'Micrófono Bloqueado',

                    console.log('🔴 Recording started');                                html: `<p><strong>Tu navegador bloqueó el micrófono</strong></p>

                                                           <p>Protocolo: ${window.location.protocol}</p>

                } catch (error) {                                       <p>Origen: ${window.location.origin}</p>

                    console.error('❌ Microphone error:', error);                                       <ol style='text-align:left;padding-left:20px;margin-top:10px'>

                                                             <li>Click en el candado 🔒 en la barra</li>

                    if (error.name === 'NotAllowedError') {                                         <li>Permitir Micrófono</li>

                        Swal.fire({                                         <li>Recarga la página (F5)</li>

                            title: 'Micrófono Bloqueado',                                       </ol>`,

                            html: `<p><strong>Pasos para permitir el micrófono:</strong></p>                                icon: 'warning',

                                   <ol style='text-align:left;padding-left:20px;margin-top:10px'>                                width: '500px'

                                     <li>Click en el <strong>candado 🔒</strong> en la barra</li>                            });

                                     <li>Busca <strong>Micrófono</strong></li>                        } else if (event.error === 'no-speech') {

                                     <li>Selecciona <strong>Permitir</strong></li>                            Swal.fire('No se detectó voz', 'Habla más cerca del micrófono', 'info');

                                     <li>Recarga la página (F5)</li>                        } else if (event.error !== 'aborted') {

                                   </ol>`,                            Swal.fire('Error', 'Error: ' + event.error, 'error');

                            icon: 'warning',                        }

                            confirmButtonText: 'Entendido'                    };

                        });

                    } else {                    window._voiceRecognition.onend = () => {

                        Swal.fire('Error', 'No se pudo acceder al micrófono: ' + error.message, 'error');                        console.log('⏹️ Recognition ended');

                    }                        this.recording = false;

                }                    };

            },                }

                

            stopRecording() {                // Reset and start SYNCHRONOUSLY

                if (this.mediaRecorder && this.recording) {                this.transcript = '';

                    console.log('⏸️ Stopping recording...');                this.extractedData = null;

                    this.mediaRecorder.stop();                this.recording = true;

                    this.recording = false;                

                }                try {

            }                    console.log('🚀 Calling .start() NOW (synchronous with click)...');

        }"                    window._voiceRecognition.start();

        x-show="open"                    console.log('✅ .start() called (waiting for onstart event...)');

        x-cloak                } catch (error) {

        @keydown.escape.window="$wire.close()"                    console.error('❌ Exception calling .start():', error);

        class="fixed inset-0 z-50 overflow-y-auto"                    this.recording = false;

        style="display: none;">                    Swal.fire('Error', error.message, 'error');

                        }

        {{-- Overlay --}}            },

        <div 

            x-show="open"            stopRecording() {

            x-transition:enter="ease-out duration-300"                if (window._voiceRecognition && this.recording) {

            x-transition:enter-start="opacity-0"                    window._voiceRecognition.stop();

            x-transition:enter-end="opacity-100"                }

            x-transition:leave="ease-in duration-200"            }

            x-transition:leave-start="opacity-100"        }"

            x-transition:leave-end="opacity-0"        x-show="open"

            @click="$wire.close()"        x-cloak

            class="fixed inset-0 bg-black bg-opacity-75"></div>        @keydown.escape.window="$wire.close()"

                class="fixed inset-0 z-50 overflow-y-auto"

        {{-- Modal Content --}}        style="display: none;">

        <div class="flex items-center justify-center min-h-screen p-4">        

            <div         {{-- Overlay --}}

                x-show="open"        <div 

                x-transition            x-show="open"

                @click.stop            x-transition:enter="ease-out duration-300"

                class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8">            x-transition:enter-start="opacity-0"

                            x-transition:enter-end="opacity-100"

                {{-- Header --}}            x-transition:leave="ease-in duration-200"

                <div class="flex items-center justify-between mb-6">            x-transition:leave-start="opacity-100"

                    <div>            x-transition:leave-end="opacity-0"

                        <h3 class="text-2xl font-bold text-gray-900">🎤 Crear Producto por Voz</h3>            @click="$wire.close()"

                        <p class="text-sm text-gray-500">Powered by OpenAI Whisper + GPT-4o-mini ✨</p>            class="fixed inset-0 bg-black bg-opacity-75"></div>

                    </div>        

                    <button @click="$wire.close()" class="text-gray-400 hover:text-gray-600">        {{-- Modal Content --}}

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">        <div class="flex items-center justify-center min-h-screen p-4">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>            <div 

                        </svg>                x-show="open"

                    </button>                x-transition

                </div>                @click.stop

                class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8">

                {{-- Instructions --}}                

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">                {{-- Header --}}

                    <p class="text-sm text-blue-800">                <div class="flex items-center justify-between mb-6">

                        <strong>Ejemplo:</strong> "Coca Cola categoría bebidas precio dos mil quinientos costo mil quinientos stock cincuenta"                    <h3 class="text-2xl font-bold text-gray-900">🎤 Crear Producto por Voz</h3>

                    </p>                    <button @click="$wire.close()" class="text-gray-400 hover:text-gray-600">

                </div>                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>

                {{-- Recording Button --}}                        </svg>

                <div class="flex flex-col items-center py-8">                    </button>

                    <button                 </div>

                        type="button"

                        @click="recording ? stopRecording() : startRecording()"                {{-- Instructions --}}

                        :disabled="processing"                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">

                        :class="recording ? 'bg-red-500 hover:bg-red-600 scale-110 animate-pulse' : 'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700'"                    <p class="text-sm text-blue-800">

                        class="w-32 h-32 rounded-full text-white shadow-xl transition-all flex items-center justify-center disabled:opacity-50">                        <strong>Ejemplo:</strong> "Coca Cola categoría bebidas precio dos mil quinientos costo mil quinientos stock cincuenta"

                        <svg x-show="!recording" class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">                    </p>

                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"></path>                </div>

                        </svg>

                        <svg x-show="recording" class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">                {{-- Recording Button --}}

                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>                <div class="flex flex-col items-center py-8">

                        </svg>                    <button 

                    </button>                        type="button"

                    <p class="mt-4 text-lg font-bold text-gray-700" x-text="recording ? '🔴 Grabando... Click para detener' : (processing ? '⏳ Procesando con IA...' : '👆 Presiona para grabar')"></p>                        @click="recording ? stopRecording() : startRecording()"

                </div>                        :disabled="processing"

                        :class="recording ? 'bg-red-500 hover:bg-red-600 scale-110' : 'bg-purple-600 hover:bg-purple-700'"

                {{-- Transcript --}}                        class="w-32 h-32 rounded-full text-white shadow-xl transition-all flex items-center justify-center disabled:opacity-50">

                <div x-show="transcript" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">                        <svg x-show="!recording" class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">

                    <p class="text-sm font-bold text-gray-700 mb-2">📝 Texto transcrito (Whisper):</p>                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"></path>

                    <p class="text-gray-900" x-text="transcript"></p>                        </svg>

                    <button                         <svg x-show="recording" class="w-16 h-16 animate-pulse" fill="currentColor" viewBox="0 0 20 20">

                        wire:click="processVoiceInput"                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>

                        :disabled="processing"                        </svg>

                        class="mt-4 w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 disabled:opacity-50">                    </button>

                        <span x-show="!processing">✨ Procesar con IA</span>                    <p class="mt-4 text-lg font-bold text-gray-700" x-text="recording ? '🔴 Grabando...' : (processing ? '⏳ Procesando...' : '👆 Presiona para grabar')"></p>

                        <span x-show="processing">⏳ Procesando...</span>                </div>

                    </button>

                </div>                {{-- Transcript --}}

                <div x-show="transcript" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">

                {{-- Extracted Data --}}                    <p class="text-sm font-bold text-gray-700 mb-2">📝 Texto capturado:</p>

                <div x-show="extractedData" class="bg-green-50 border border-green-300 rounded-lg p-6">                    <p class="text-gray-900" x-text="transcript"></p>

                    <p class="text-lg font-bold text-green-900 mb-4">✅ Producto detectado:</p>                    <button 

                                            wire:click="processVoiceInput"

                    <div class="grid grid-cols-2 gap-4 mb-4">                        :disabled="processing"

                        <div>                        class="mt-4 w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 disabled:opacity-50">

                            <p class="text-xs text-green-700">Nombre:</p>                        <span x-show="!processing">✨ Procesar con IA</span>

                            <p class="font-bold text-green-900" x-text="extractedData?.nombre || '-'"></p>                        <span x-show="processing">⏳ Procesando...</span>

                        </div>                    </button>

                        <div>                </div>

                            <p class="text-xs text-green-700">Categoría:</p>

                            <p class="font-bold text-green-900" x-text="extractedData?.categoria || '-'"></p>                {{-- Extracted Data --}}

                        </div>                <div x-show="extractedData" class="bg-green-50 border border-green-300 rounded-lg p-6">

                        <div>                    <p class="text-lg font-bold text-green-900 mb-4">✅ Producto detectado:</p>

                            <p class="text-xs text-green-700">Precio:</p>                    

                            <p class="font-bold text-green-900" x-text="extractedData?.precio ? '$' + extractedData.precio.toLocaleString() : '-'"></p>                    <div class="grid grid-cols-2 gap-4 mb-4">

                        </div>                        <div>

                        <div>                            <p class="text-xs text-green-700">Nombre:</p>

                            <p class="text-xs text-green-700">Costo:</p>                            <p class="font-bold text-green-900" x-text="extractedData?.nombre || '-'"></p>

                            <p class="font-bold text-green-900" x-text="extractedData?.costo ? '$' + extractedData.costo.toLocaleString() : '-'"></p>                        </div>

                        </div>                        <div>

                        <div class="col-span-2">                            <p class="text-xs text-green-700">Categoría:</p>

                            <p class="text-xs text-green-700">Stock:</p>                            <p class="font-bold text-green-900" x-text="extractedData?.categoria || '-'"></p>

                            <p class="font-bold text-green-900" x-text="(extractedData?.stock || 0) + ' unidades'"></p>                        </div>

                        </div>                        <div>

                    </div>                            <p class="text-xs text-green-700">Precio:</p>

                            <p class="font-bold text-green-900" x-text="extractedData?.precio ? '$' + extractedData.precio.toLocaleString() : '-'"></p>

                    <div class="flex gap-3">                        </div>

                        <button                         <div>

                            wire:click="createFromVoice"                            <p class="text-xs text-green-700">Costo:</p>

                            class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">                            <p class="font-bold text-green-900" x-text="extractedData?.costo ? '$' + extractedData.costo.toLocaleString() : '-'"></p>

                            ✅ Crear Producto                        </div>

                        </button>                    </div>

                        <button 

                            wire:click="cancelVoiceInput"                    <div class="flex gap-3">

                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">                        <button 

                            🔄 Reintentar                            wire:click="createFromVoice"

                        </button>                            class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">

                    </div>                            ✅ Crear Producto

                </div>                        </button>

            </div>                        <button 

        </div>                            wire:click="cancelVoiceInput"

    </div>                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">

                            🔄 Reintentar

    {{-- Event Listeners --}}                        </button>

    @script                    </div>

    <script>                </div>

        $wire.on('voice-product-created', (event) => {            </div>

            Swal.fire({        </div>

                icon: 'success',    </div>

                title: '✨ Producto Creado!',

                text: event.productName,    {{-- Event Listeners --}}

                toast: true,    @script

                position: 'top-end',    <script>

                timer: 3000,        $wire.on('voice-product-created', (event) => {

                showConfirmButton: false            Swal.fire({

            });                icon: 'success',

        });                title: '✨ Producto Creado!',

                text: event.productName,

        $wire.on('voice-error', (event) => {                toast: true,

            Swal.fire({                position: 'top-end',

                icon: 'error',                timer: 3000,

                title: 'Error',                showConfirmButton: false

                text: event.message            });

            });        });

        });

    </script>        $wire.on('voice-error', (event) => {

    @endscript            Swal.fire({

</div>                icon: 'error',

                title: 'Error',
                text: event.message
            });
        });
    </script>
    @endscript
</div>
