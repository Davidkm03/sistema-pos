{{-- Modal de Creación por Voz con MediaRecorder + Whisper --}}
<div>
    <div 
        x-data="{
            open: @entangle('showModal'),
            recording: false,
            mediaRecorder: null,
            audioChunks: [],
            transcript: @entangle('voiceTranscript').live,
            processing: @entangle('voiceProcessing').live,
            extractedData: @entangle('voiceExtractedData').live,

            init() {
                console.log('🎤 VoiceProductCreator initialized');
            },

            async startRecording() {
                console.log('🎤 Requesting microphone...');
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    console.log('✅ Microphone granted');
                    
                    this.audioChunks = [];
                    this.mediaRecorder = new MediaRecorder(stream);
                    
                    this.mediaRecorder.ondataavailable = (event) => {
                        if (event.data.size > 0) {
                            this.audioChunks.push(event.data);
                        }
                    };
                    
                    this.mediaRecorder.onstop = async () => {
                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                        const reader = new FileReader();
                        reader.readAsDataURL(audioBlob);
                        reader.onloadend = async () => {
                            const base64Audio = reader.result.split(',')[1];
                            await $wire.transcribeAudio(base64Audio);
                        };
                        stream.getTracks().forEach(track => track.stop());
                    };
                    
                    this.mediaRecorder.start();
                    this.recording = true;
                } catch (error) {
                    console.error('❌ Microphone error:', error);
                    if (error.name === 'NotAllowedError') {
                        Swal.fire({
                            title: 'Micrófono Bloqueado',
                            html: '<p>Pasos para permitir:</p><ol><li>Click en el candado 🔒</li><li>Permitir Micrófono</li><li>Recarga (F5)</li></ol>',
                            icon: 'warning'
                        });
                    }
                }
            },

            stopRecording() {
                if (this.mediaRecorder && this.recording) {
                    this.mediaRecorder.stop();
                    this.recording = false;
                }
            },

            startTutorial() {
                if (typeof driver === 'undefined') {
                    console.error('Driver.js no cargado');
                    return;
                }

                const driverObj = driver({
                    showProgress: true,
                    steps: [
                        {
                            element: '.voice-record-btn',
                            popover: {
                                title: '🎤 Paso 1: Grabar',
                                description: 'Presiona este botón y habla claramente. Di el nombre, categoría, precio, costo y stock del producto.',
                                position: 'bottom'
                            }
                        },
                        {
                            element: '.voice-example-box',
                            popover: {
                                title: '💡 Ejemplo de uso',
                                description: 'Di el nombre del producto, su categoría, precio, costo y cantidad de stock disponible.',
                                position: 'top'
                            }
                        },
                        {
                            popover: {
                                title: '✨ Paso 2: Procesar',
                                description: 'Después de grabar, el texto aparecerá. Click en "Procesar con IA" para que GPT-4o-mini extraiga los datos estructurados.'
                            }
                        },
                        {
                            popover: {
                                title: '✅ Paso 3: Crear',
                                description: 'Verifica los datos extraídos y presiona "Crear Producto". Si la categoría no existe, se creará automáticamente.'
                            }
                        },
                        {
                            popover: {
                                title: '🚀 ¡Listo!',
                                description: 'Tu producto se creará en segundos. Puedes crear múltiples productos rápidamente usando solo tu voz.',
                                position: 'center'
                            }
                        }
                    ]
                });

                driverObj.drive();
            }
        }"
        x-show="open"
        x-cloak
        @keydown.escape.window="$wire.close()"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <div x-show="open" x-transition @click="$wire.close()" class="fixed inset-0 bg-black bg-opacity-75"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="open" x-transition @click.stop class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8">
                
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">🎤 Crear Producto por Voz</h3>
                        <p class="text-sm text-gray-500">OpenAI Whisper + GPT-4o-mini ✨</p>
                    </div>
                    <div class="flex gap-2">
                        <button 
                            @click="startTutorial()" 
                            class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50"
                            title="Ver tutorial">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        <button @click="$wire.close()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 voice-example-box">
                    <p class="text-sm text-blue-800">
                        <strong>Ejemplo:</strong> "Coca Cola categoría bebidas precio dos mil quinientos costo mil quinientos stock cincuenta"
                    </p>
                </div>

                <div class="flex flex-col items-center py-8">
                    <button 
                        type="button"
                        @click="recording ? stopRecording() : startRecording()"
                        :disabled="processing"
                        :class="recording ? 'bg-red-500 hover:bg-red-600 scale-110' : 'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700'"
                        class="w-32 h-32 rounded-full text-white shadow-xl transition-all flex items-center justify-center disabled:opacity-50 voice-record-btn">
                        <svg x-show="!recording" class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"></path>
                        </svg>
                        <svg x-show="recording" class="w-16 h-16 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <p class="mt-4 text-lg font-bold text-gray-700" x-text="recording ? '🔴 Grabando...' : (processing ? '⏳ Procesando...' : '👆 Presiona para grabar')"></p>
                </div>

                <div x-show="transcript" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                    <p class="text-sm font-bold text-gray-700 mb-2">📝 Transcrito:</p>
                    <p class="text-gray-900" x-text="transcript"></p>
                    <button 
                        wire:click="processVoiceInput"
                        :disabled="processing"
                        class="mt-4 w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 disabled:opacity-50">
                        <span x-show="!processing">✨ Procesar con IA</span>
                        <span x-show="processing">⏳ Procesando...</span>
                    </button>
                </div>

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
                        <div class="col-span-2">
                            <p class="text-xs text-green-700">Stock:</p>
                            <p class="font-bold text-green-900" x-text="(extractedData?.stock || 0) + ' unidades'"></p>
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

    {{-- Driver.js CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css"/>
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.iife.js"></script>

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
