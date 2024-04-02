# otp-input-livewire-alphinejs
Create OTP Input with Resend CountDown Timer in Livewire using Alphinejs and TailwindCss

If you're working with Laravel Livewire and need to create an OTP Input. Here is how to create a OTP input with a count down timer that has a resend button using Alphine.js and TailwindCss.

#### Design the Input Field
The code below creates a single input field for entering a single number. The number of fields depends on the length of the OTP code. The next part will show you how to display the number of input as required.

```
<form class="fi-form grid gap-y-6">
<input
type="tel"
maxlength="1"
class="border border-gray-500 w-10 h-10 text-center"
/>
@error('code')
  <p data-validation-error="" class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">
   {{ $message }}
  </p>
@enderror
<button 
    style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
   type="submit"
>Verify</button>
</form>
```

#### Display Specific Number of Input fields
Here we use javascript (alphinejs) to specify number of boxes to display and use for loop to display them. 

```
<form 
  class="fi-form grid gap-y-6"
  wire:submit="verify"
  x-data="otpForm()"
>
    <div style="--cols-default: repeat(1, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
            <div data-field-wrapper="" class="fi-fo-field-wrp">
    
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

            </div>
        </div>

    </div> 
    </div>  
    </form>

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
</script>
@endpush
```
We passed the javascript function to the form as `x-data`. This will allow us access all the variables and functions inside the `otpForm()` function inside the form element. 
The OtpForm function contains 
_the `length` variable,_ that will show the number of boxes to display.
_`handleInput` function_ concatenates otp codes entered in all the boxes and stores it to the otp variable.
_`handlePaste` function_ helps to transfer copied otp content from clipboard to the boxes.
_`handleDelete` function_ helps handle deleting of contents of the otp boxes and refocuses the cursor.

We used `:x-ref` to uniquely identify each input box and then used `document.querySelector` to retrieve the value of each of the boxes based on their position.

We also added a hidden field to store the otp code before submitting to our model.

```
<input 
  type="hidden" 
  id="otp" 
  wire:model="code"
  required="required"
  x-model="otp"
>
```

#### Add Resend Button with CountDown Timer
Let's add a resend button with countdown timer.

```
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

<script>
function otpSend(num) {
            const milliseconds = num * 1000 //60 seconds
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
```
The `otpSend(num)` function uses a countdown timer to only display the resend button after specified time `num` in seconds.

