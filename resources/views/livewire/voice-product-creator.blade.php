<div>
    {{-- Modal de Creación por Voz (Componente Independiente) --}}

    {{-- CRITICAL: Store recognition OUTSIDE Alpine.js scope to avoid Proxy wrapping --}}
    <script>
        window._voiceRecognition = null;
    </script>

    <div 
        x-data="{
            open: @entangle('showModal'),
            recording: false,
            transcript: @entangle('voiceTranscript').live,
            processing: @entangle('voiceProcessing').live,
            extractedData: @entangle('voiceExtractedData').live,

            init() {
                console.log('VoiceProductCreator Alpine init - Modal should be closed');
                console.log('Recognition stored globally:', window._voiceRecognition);
            },

            startRecording() {
                console.log('🎤 User clicked record button');
                console.log('Protocol:', window.location.protocol, '| HTTPS:', window.location.protocol === 'https:');
                
                // Use GLOBAL variable to avoid Alpine.js Proxy wrapping the native API
                if (!window._voiceRecognition) {
                    if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
                        console.error('❌ Speech Recognition NOT supported');
                        Swal.fire('No Soportado', 'Tu navegador no soporta reconocimiento de voz.', 'error');
                        return;
                    }

                    console.log('✅ Creating Speech Recognition instance...');
                    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                    window._voiceRecognition = new SR();
                    window._voiceRecognition.lang = 'es-CO';
                    window._voiceRecognition.continuous = false;
                    window._voiceRecognition.interimResults = false;

                    console.log('✅ Recognition object created (stored globally)');

                    window._voiceRecognition.onstart = () => {
                        console.log('✅✅✅ Recognition STARTED successfully!');
                    };

                    window._voiceRecognition.onresult = (event) => {
                        console.log('✅ Recognition RESULT:', event.results[0][0].transcript);
                        this.transcript = event.results[0][0].transcript;
                        this.recording = false;
                    };

                    window._voiceRecognition.onerror = (event) => {
                        console.error('❌ Speech error:', event.error, event);
                        this.recording = false;
                        
                        if (event.error === 'not-allowed') {
                            Swal.fire({
                                title: 'Micrófono Bloqueado',
                                html: `<p><strong>Tu navegador bloqueó el micrófono</strong></p>
                                       <p>Protocolo: ${window.location.protocol}</p>
                                       <p>Origen: ${window.location.origin}</p>
                                       <ol style='text-align:left;padding-left:20px;margin-top:10px'>
                                         <li>Click en el candado 🔒 en la barra</li>
                                         <li>Permitir Micrófono</li>
                                         <li>Recarga la página (F5)</li>
                                       </ol>`,
                                icon: 'warning',
                                width: '500px'
                            });
                        } else if (event.error === 'no-speech') {
                            Swal.fire('No se detectó voz', 'Habla más cerca del micrófono', 'info');
                        } else if (event.error !== 'aborted') {
                            Swal.fire('Error', 'Error: ' + event.error, 'error');
                        }
                    };

                    window._voiceRecognition.onend = () => {
                        console.log('⏹️ Recognition ended');
                        this.recording = false;
                    };
                }
                
                // Reset and start SYNCHRONOUSLY
                this.transcript = '';
                this.extractedData = null;
                this.recording = true;
                
                try {
                    console.log('🚀 Calling .start() NOW (synchronous with click)...');
                    window._voiceRecognition.start();
                    console.log('✅ .start() called (waiting for onstart event...)');
                } catch (error) {
                    console.error('❌ Exception calling .start():', error);
                    this.recording = false;
                    Swal.fire('Error', error.message, 'error');
                }
            },

            stopRecording() {
                if (window._voiceRecognition && this.recording) {
                    window._voiceRecognition.stop();
                }
            }
        }"
        x-show="open"
        x-cloak
        @keydown.escape.window="$wire.close()"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        {{-- Overlay --}}
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="$wire.close()"
            class="fixed inset-0 bg-black bg-opacity-75"></div>
        
        {{-- Modal Content --}}
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                x-show="open"
                x-transition
                @click.stop
                class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8">
                
                {{-- Header --}}
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">🎤 Crear Producto por Voz</h3>
                    <button @click="$wire.close()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Instructions --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>Ejemplo:</strong> "Coca Cola categoría bebidas precio dos mil quinientos costo mil quinientos stock cincuenta"
                    </p>
                </div>

                {{-- Recording Button --}}
                <div class="flex flex-col items-center py-8">
                    <button 
                        type="button"
                        @click="recording ? stopRecording() : startRecording()"
                        :disabled="processing"
                        :class="recording ? 'bg-red-500 hover:bg-red-600 scale-110' : 'bg-purple-600 hover:bg-purple-700'"
                        class="w-32 h-32 rounded-full text-white shadow-xl transition-all flex items-center justify-center disabled:opacity-50">
                        <svg x-show="!recording" class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"></path>
                        </svg>
                        <svg x-show="recording" class="w-16 h-16 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <p class="mt-4 text-lg font-bold text-gray-700" x-text="recording ? '🔴 Grabando...' : (processing ? '⏳ Procesando...' : '👆 Presiona para grabar')"></p>
                </div>

                {{-- Transcript --}}
                <div x-show="transcript" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                    <p class="text-sm font-bold text-gray-700 mb-2">📝 Texto capturado:</p>
                    <p class="text-gray-900" x-text="transcript"></p>
                    <button 
                        wire:click="processVoiceInput"
                        :disabled="processing"
                        class="mt-4 w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 disabled:opacity-50">
                        <span x-show="!processing">✨ Procesar con IA</span>
                        <span x-show="processing">⏳ Procesando...</span>
                    </button>
                </div>

                {{-- Extracted Data --}}
                <div x-show="extractedData" class="bg-green-50 border border-green-300 rounded-lg p-6">
                    <p class="text-lg font-bold text-green-900 mb-4">✅ Producto detectado:</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-green-700">Nombre:</p>
                            <p class="font-bold text-green-900" x-text="extractedData?.nombre || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-green-700">Categoría:</p>
                            <p class="font-bold text-green-900" x-text="extractedData?.categoria || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-green-700">Precio:</p>
                            <p class="font-bold text-green-900" x-text="extractedData?.precio ? '$' + extractedData.precio.toLocaleString() : '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs text-green-700">Costo:</p>
                            <p class="font-bold text-green-900" x-text="extractedData?.costo ? '$' + extractedData.costo.toLocaleString() : '-'"></p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button 
                            wire:click="createFromVoice"
                            class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">
                            ✅ Crear Producto
                        </button>
                        <button 
                            wire:click="cancelVoiceInput"
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">
                            🔄 Reintentar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Event Listeners --}}
    @script
    <script>
        $wire.on('voice-product-created', (event) => {
            Swal.fire({
                icon: 'success',
                title: '✨ Producto Creado!',
                text: event.productName,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
        });

        $wire.on('voice-error', (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: event.message
            });
        });
    </script>
    @endscript
</div>
