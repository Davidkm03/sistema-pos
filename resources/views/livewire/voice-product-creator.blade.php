<div>
    {{-- Modal de Creación por Voz (Componente Independiente) --}}

    <div 
        x-data="{
            open: @entangle('showModal'),
            recording: false,
            recognition: null,
            transcript: @entangle('voiceTranscript').live,
            processing: @entangle('voiceProcessing').live,
            extractedData: @entangle('voiceExtractedData').live,

            init() {
                console.log('VoiceProductCreator Alpine init - Modal should be closed');
                
                // NO inicializar Speech Recognition aquí - solo cuando el usuario haga click
                // La API requiere interacción del usuario (user gesture) para pedir permisos
            },

            startRecording() {
                console.log('User clicked record button - initializing Speech Recognition');
                
                // Inicializar Speech Recognition SOLO cuando el usuario hace click (user gesture required)
                if (!this.recognition) {
                    if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
                        Swal.fire('No Soportado', 'Tu navegador no soporta reconocimiento de voz. Usa Chrome, Edge o Safari.', 'error');
                        return;
                    }

                    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                    this.recognition = new SR();
                    this.recognition.lang = 'es-CO';
                    this.recognition.continuous = false;
                    this.recognition.interimResults = false;

                    this.recognition.onresult = (event) => {
                        this.transcript = event.results[0][0].transcript;
                        this.recording = false;
                        console.log('Speech recognized:', this.transcript);
                    };

                    this.recognition.onerror = (event) => {
                        console.error('Speech error:', event.error);
                        this.recording = false;
                        
                        if (event.error === 'not-allowed') {
                            Swal.fire({
                                title: 'Micrófono Bloqueado',
                                html: `<p><strong>Pasos para permitir el micrófono:</strong></p>
                                       <ol style='text-align:left;padding-left:20px;margin-top:10px'>
                                         <li>Click en el <strong>candado 🔒</strong> en la barra de direcciones</li>
                                         <li>Busca <strong>Micrófono</strong></li>
                                         <li>Selecciona <strong>Permitir</strong></li>
                                         <li>Recarga la página (F5)</li>
                                       </ol>
                                       <p style='margin-top:15px'><strong>Nota:</strong> Asegúrate de usar HTTPS (candado en la URL)</p>`,
                                icon: 'warning',
                                confirmButtonText: 'Entendido',
                                width: '500px'
                            });
                        } else if (event.error === 'no-speech') {
                            Swal.fire({
                                title: 'No se detectó voz',
                                text: 'Habla más cerca del micrófono',
                                icon: 'info'
                            });
                        } else if (event.error !== 'aborted') {
                            Swal.fire('Error', 'Error: ' + event.error, 'error');
                        }
                    };

                    this.recognition.onend = () => {
                        this.recording = false;
                        console.log('Speech recognition ended');
                    };
                }
                
                // Resetear y empezar INMEDIATAMENTE (sin setTimeout - debe ser síncrono con el click)
                this.transcript = '';
                this.extractedData = null;
                this.recording = true;
                
                // START debe ser síncrono con el click del usuario para que el navegador permita el micrófono
                try {
                    this.recognition.start();
                    console.log('Speech recognition started successfully');
                } catch (error) {
                    console.error('Error starting recognition:', error);
                    this.recording = false;
                    Swal.fire('Error', 'No se pudo iniciar: ' + error.message, 'error');
                }
            },

            stopRecording() {
                if (this.recognition && this.recording) {
                    this.recognition.stop();
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
