<h1 class="fi-simple-header-heading text-center text-xl font-bold tracking-tight text-gray-950 dark:text-white">
  Verify OTP
</h1>
<div class="grid auto-cols-fr gap-y-6">    
    <form 
        class="fi-form grid gap-y-6"
        wire:submit="verify"
        x-data="otpForm()"
    >
    <div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
        
        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
            <div data-field-wrapper="" class="fi-fo-field-wrp">
    
            <div class="grid gap-y-2">
                @error('error')
                    <div 
                        data-validation-error="" 
                        class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400 text-center" 
                        style="background:#f4ece0;padding:10px;margin-top: 20px;border: solid 1px;">
                        {{ $message }}
                    </div>
                @enderror
                <div class="flex items-center justify-between gap-x-3 ">
                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="data.otp">
    
                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        Enter OTP Code sent to your email <sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                    </span>

                </label>
            </div>
            <div class="grid gap-y-2">
                <input 
                    type="hidden" 
                    id="otp" 
                    wire:model="code"
                    required="required"
                    x-model="otp"
                >
            </div>
            <div class="grid gap-y-2">
                <div class="">
                    <div class="py-6 px-0 w-80 mx-auto text-center my-6">
                        <div class="flex justify-between">
                            <template x-for="(input, index) in length" :key="index">
                                <input
                                    type="tel"
                                    maxlength="1"
                                    class="border border-gray-500 w-10 h-10 text-center"
                                    :x-ref="index"
                                    x-on:input="handleInput($event)"
                                    x-on:paste="handlePaste($event)"
                                    x-on:keyup="handleDelete($event)"
                                />
                            </template>
                        </div>
                    </div>
                </div>

                @error('code')
                <p data-validation-error="" class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">
                {{ $message }}
                </p>
                @enderror

            </div>
            <div class="grid gap-y-2">
                <div class="fi-form-actions">
                    <div class="fi-ac gap-3 grid grid-cols-[repeat(auto-fit,minmax(0,1fr))]">
                        <button 
                            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                            class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
                            type="submit"
                            x-on:click="$wire.code=document.getElementById('otp').value"
                        >
                            Verify
                        </button>
                    </div>
                </div>
            </div>
            <div class="grid gap-y-2 text-center" x-data="otpSend(80)" x-init="init()">
            <template  x-if="getTime() <= 0">
            <form wire:submit="resendOtp">
                <button
                    type="submit"
                >
                    Resend OTP
                    <div wire:loading>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                                <circle fill="#FF156D" stroke="#FF156D" stroke-width="15" r="15" cx="40" cy="100">
                                    <animate attributeName="opacity" calcMode="spline" dur="2" values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4">
                                    </animate>
                                </circle>
                                <circle fill="#FF156D" stroke="#FF156D" stroke-width="15" r="15" cx="100" cy="100">
                                    <animate attributeName="opacity" calcMode="spline" dur="2" values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2">
                                    </animate>
                                </circle>
                                    <circle fill="#FF156D" stroke="#FF156D" stroke-width="15" r="15" cx="160" cy="100">
                                        <animate attributeName="opacity" calcMode="spline" dur="2" values="1;0;1;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0">
                                        </animate>
                                </circle>
                            </svg>

                            </div>
                </button>
                <input type="hidden" wire:model="logid">
            </form>
            </template>
            <template x-if="getTime() > 0">
            <small>
                Resend OTP in 
                <span x-text="formatTime(getTime())"></span>
            </small>
            </template>
            </div>
            </div>
        </div>

    </div> 
    </div>  
    </form>
</div>
    
    
    @push('scripts')
    <script>
        function otpForm() {
            return {
                    length: 7,
                    otp: '',

                    handleInput(e) {
   
                        const input = e.target;

                        this.otp = Array.from(Array(this.length), (element, i) => {
                            let ref = document.querySelector('[x-ref="' + i + '"]');
                            return ref.value || '';
                        }).join('');

                        if (input.nextElementSibling && input.value) {
                            input.nextElementSibling.focus();
                            input.nextElementSibling.select();
                        }
                    },

                    handlePaste(e) {
                        const paste = e.clipboardData.getData('text');
                        this.otp = paste;

                        const inputs = Array.from(Array(this.length));

                        inputs.forEach((element, i) => {
                            let ref = document.querySelector('[x-ref="' + i + '"]');
                            ref.value = paste[i] || '';
                        });
                    },

                    handleDelete(e) {
                        let key = e.keyCode || e.charCode;
                        if(key == 8 || key == 46) {
                            currentRef = e.target.getAttribute('x-ref');
                            const previous = parseInt(currentRef) - 1;
                            
                            let ref = document.querySelector('[x-ref="' + previous + '"]');
                            ref && ref.focus();
                        }
                    },
            }
        }


        function otpSend(num) {
            const milliseconds = num * 1000
            const currentDate = Date.now() + milliseconds
            var countDownTime = new Date(currentDate).getTime()
            let interval;

            return {
                countDown: milliseconds,
                countDownTimer: new Date(currentDate).getTime(),
                intervalID: null,
                init(){
                    if (!this.intervalID ) {
                        this.intervalID = setInterval(() => {
                            this.countDown = this.countDownTimer - new Date().getTime();
                        }, 1000);
                    }

                },
                getTime(){
                    if(this.countDown < 0){
                        this.clearTimer()
                    }
                    return this.countDown;

                },
                formatTime(num){

                    var date = new Date(num);
                    return new Date(this.countDown).toLocaleTimeString(navigator.language, {
                        minute: '2-digit',
                        second:'2-digit'
                    });

                },
                clearTimer() {
                    clearInterval(this.intervalID);
                }
                
            }
        }

        </script>

    @endpush
