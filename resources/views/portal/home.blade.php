@extends('layouts.portal')
@section('title', __('common.home'))

@section('content')


      <!-- tp-hero-med-area-start -->
      <div class="tp-hero-med-area bg-position fix p-relative" data-background="../assets/img/medical/hero/image.jpg">
        <img class="tp-hero-med-shape p-absolute updown d-none d-md-block" src="../assets-portal/img/medical/hero/shape-2.png" alt="">
        <div class="container container-1230">
           <!-- Language Switcher -->
           <div class="d-flex justify-content-end pt-3">
              <x-language-switcher />
           </div>

           <div class="row gx-24 align-items-end">
              <div class="col-xl-5 col-lg-6">
                 <div class="tp-hero-med-content tp-hero-med-spacing"  data-anime='{ "el": "childs", "rotateX": [90, 0], "opacity": [0,1], "staggervalue": 150, "easing": "easeOutQuad" }'>
                    <h2 class="tp-ff-poppins fw-600 mb-25 fs-80 fs-xl-70 fs-xs-50 ls-m-4 tp-text-common-black-4 lh-1">
                       {{ __('medical.clinic') }} {{ __('common.services') }}
                       <span class="fw-500 tp-hero-med-text-underline lh-1">{{ __('medical.treatment') }}</span>
                    </h2>
                    <p class="tp-text-grey-9 lh-26 tp-ff-roboto ls-0 mb-40">{{ __('medical.clinic_description', ['name' => 'Poliklinik Al-Huda']) }}</p>
                    <div class="tp-hero-med-btn-wrap d-flex flex-wrap mb-150">
                       <a href="appointment.html" class="med-btn-shadow mb-15 tp-btn-switch-text btn-lg mr-20 bg-hover-black p-relative tp-round-100 d-inline-block hover-text-white tp-bg-theme-1 tp-text-common-white fw-500 tp-ff-poppins lh-26 ls-m-2">
                          <span>
                             <span class="mr-5">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M11.0133 3.7666L8.94665 2.1666C8.67998 1.89993 8.07998 1.7666 7.67998 1.7666H5.14665C4.34665 1.7666 3.47998 2.3666 3.27998 3.1666L1.67998 8.03327C1.34665 8.9666 1.94665 9.7666 2.94665 9.7666H5.61332C6.01332 9.7666 6.34665 10.0999 6.27998 10.5666L5.94665 12.6999C5.81332 13.2999 6.21332 13.9666 6.81332 14.1666C7.34665 14.3666 8.01332 14.0999 8.27998 13.6999L11.0133 9.63327" stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                                   <path d="M14.4133 3.76667V10.3C14.4133 11.2333 14.0133 11.5667 13.08 11.5667H12.4133C11.48 11.5667 11.08 11.2333 11.08 10.3V3.76667C11.08 2.83333 11.48 2.5 12.4133 2.5H13.08C14.0133 2.5 14.4133 2.83333 14.4133 3.76667Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                             </span>
                             <span class="btn-double-text" data-text="Book an Appointment">Book an Appointment</span>
                          </span>
                       </a>
                       <a href="#" class="tp-btn-switch-text mb-15 btn-lg bg-hover-theme-1 p-relative tp-round-100 d-inline-block hover-text-white tp-bg-common-white tp-text-common-black-4 fw-500 tp-ff-poppins lh-26 ls-m-2">
                          <span>
                             <span class="mr-5">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M8.35334 13.6134H4.14001C2.03334 13.6134 1.33334 12.2134 1.33334 10.8067V5.19339C1.33334 3.08672 2.03334 2.38672 4.14001 2.38672H8.35334C10.46 2.38672 11.16 3.08672 11.16 5.19339V10.8067C11.16 12.9134 10.4533 13.6134 8.35334 13.6134Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                   <path d="M13.0133 11.3998L11.16 10.0998V5.89318L13.0133 4.59318C13.92 3.95984 14.6667 4.34651 14.6667 5.45984V10.5398C14.6667 11.6532 13.92 12.0398 13.0133 11.3998Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                             </span>
                             <span class="btn-double-text" data-text="Video Call">Video Call</span>
                          </span>
                       </a>
                    </div>
                    <div class="d-flex flex-wrap mb-30">
                       <span class="tp-ff-poppins mr-45 mb-10 fw-500 ls-m-2 tp-text-common-black-2 d-flex align-items-end">
                          <svg class="mr-10" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                             <g clip-path="url(#clip0_940_503)">
                               <path d="M22.9999 11.0006H20.9999V9.00061C20.9999 8.73539 20.8945 8.48104 20.707 8.2935C20.5194 8.10597 20.2651 8.00061 19.9999 8.00061C19.7347 8.00061 19.4803 8.10597 19.2928 8.2935C19.1052 8.48104 18.9999 8.73539 18.9999 9.00061V11.0006H16.9999C16.7347 11.0006 16.4803 11.106 16.2928 11.2935C16.1052 11.481 15.9999 11.7354 15.9999 12.0006C15.9999 12.2658 16.1052 12.5202 16.2928 12.7077C16.4803 12.8952 16.7347 13.0006 16.9999 13.0006H18.9999V15.0006C18.9999 15.2658 19.1052 15.5202 19.2928 15.7077C19.4803 15.8952 19.7347 16.0006 19.9999 16.0006C20.2651 16.0006 20.5194 15.8952 20.707 15.7077C20.8945 15.5202 20.9999 15.2658 20.9999 15.0006V13.0006H22.9999C23.2651 13.0006 23.5194 12.8952 23.707 12.7077C23.8945 12.5202 23.9999 12.2658 23.9999 12.0006C23.9999 11.7354 23.8945 11.481 23.707 11.2935C23.5194 11.106 23.2651 11.0006 22.9999 11.0006Z" fill="#6C60DC" />
                               <path d="M9 11.9999C10.1867 11.9999 11.3467 11.648 12.3334 10.9887C13.3201 10.3294 14.0892 9.39234 14.5433 8.29598C14.9974 7.19963 15.1162 5.99323 14.8847 4.82934C14.6532 3.66545 14.0818 2.59635 13.2426 1.75724C12.4035 0.918125 11.3344 0.34668 10.1705 0.115169C9.00666 -0.116342 7.80026 0.00247765 6.7039 0.456603C5.60754 0.910729 4.67047 1.67976 4.01118 2.66646C3.35189 3.65315 3 4.81319 3 5.99988C3.00159 7.59069 3.63424 9.11589 4.75911 10.2408C5.88399 11.3656 7.40919 11.9983 9 11.9999ZM9 1.99988C9.79113 1.99988 10.5645 2.23448 11.2223 2.674C11.8801 3.11353 12.3928 3.73824 12.6955 4.46915C12.9983 5.20005 13.0775 6.00432 12.9231 6.78024C12.7688 7.55617 12.3878 8.2689 11.8284 8.82831C11.269 9.38772 10.5563 9.76868 9.78036 9.92302C9.00444 10.0774 8.20017 9.99815 7.46927 9.6954C6.73836 9.39265 6.11365 8.87996 5.67412 8.22216C5.2346 7.56436 5 6.79101 5 5.99988C5 4.93901 5.42143 3.9216 6.17157 3.17145C6.92172 2.42131 7.93913 1.99988 9 1.99988Z" fill="#6C60DC" />
                               <path d="M9 14.0006C6.61386 14.0033 4.32622 14.9523 2.63896 16.6396C0.951708 18.3268 0.00264685 20.6145 0 23.0006C0 23.2658 0.105357 23.5202 0.292893 23.7077C0.48043 23.8953 0.734784 24.0006 1 24.0006C1.26522 24.0006 1.51957 23.8953 1.70711 23.7077C1.89464 23.5202 2 23.2658 2 23.0006C2 21.1441 2.7375 19.3636 4.05025 18.0509C5.36301 16.7381 7.14348 16.0006 9 16.0006C10.8565 16.0006 12.637 16.7381 13.9497 18.0509C15.2625 19.3636 16 21.1441 16 23.0006C16 23.2658 16.1054 23.5202 16.2929 23.7077C16.4804 23.8953 16.7348 24.0006 17 24.0006C17.2652 24.0006 17.5196 23.8953 17.7071 23.7077C17.8946 23.5202 18 23.2658 18 23.0006C17.9974 20.6145 17.0483 18.3268 15.361 16.6396C13.6738 14.9523 11.3861 14.0033 9 14.0006Z" fill="#6C60DC" />
                             </g>
                             <defs>
                               <clipPath id="clip0_940_503">
                                 <rect width="24" height="24" fill="white" />
                               </clipPath>
                             </defs>
                          </svg>
                          Talk to over 285 doctors</span>
                          <span class="tp-ff-poppins mb-10 fw-500 ls-m-2 tp-text-common-black-2 d-flex align-items-end">
                             <svg class="mr-10" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.86 8.08997C19.86 8.50997 19.83 8.91997 19.78 9.30997C19.32 9.10997 18.82 8.99997 18.29 8.99997C17.07 8.99997 15.99 9.58996 15.32 10.49C14.64 9.58996 13.56 8.99997 12.34 8.99997C10.29 8.99997 8.63 10.67 8.63 12.74C8.63 15.42 10.05 17.47 11.63 18.86C11.58 18.89 11.53 18.9 11.48 18.92C11.18 19.03 10.68 19.03 10.38 18.92C7.79 18.03 2 14.35 2 8.08997C2 5.32997 4.21999 3.09998 6.95999 3.09998C8.58999 3.09998 10.03 3.87997 10.93 5.08997C11.84 3.87997 13.28 3.09998 14.9 3.09998C17.64 3.09998 19.86 5.32997 19.86 8.08997Z" stroke="#6C60DC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 12.74C22 17.42 17.67 20.18 15.73 20.84C15.5 20.92 15.13 20.92 14.9 20.84C14.07 20.56 12.8 19.89 11.63 18.86C10.05 17.47 8.63 15.42 8.63 12.74C8.63 10.67 10.29 9 12.34 9C13.56 9 14.64 9.58999 15.32 10.49C15.99 9.58999 17.07 9 18.29 9C18.82 9 19.32 9.11 19.78 9.31C21.09 9.89 22 11.2 22 12.74Z" stroke="#6C60DC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                             </svg>
                             96% Satisfaction rate
                          </span>
                    </div>
                 </div>
              </div>
              <div class="col-xl-7 col-lg-6">
                 <div class="tp-hero-med-right text-end p-relative">
                    <img class="tp-hero-med-heart animate-pulse rounded-circle z-index-9" src="../assets-portal/img/medical/hero/heart.png" alt="">
                    <div class="tp-hero-med-thumb">
                       <img data-parallax='{"y": 300, "smoothness":15}' src="../assets-portal/img/medical/hero/afiez.jpg" alt="doctor">
                    </div>
                    <div class="tp-hero-med-checkup d-flex align-items-center p-absolute updown">
                       <span class="fw-500 ls-m-2 tp-text-common-white"><i class="fa-sharp fa-solid fa-check mr-5 tp-bg-theme-1 tp-text-common-white text-center fs-14"></i> Regular checkup service</span>
                     </div>
                    <div class="tp-hero-med-thumb-shape tp-bg-theme-1 tp-round-25 text-center p-absolute d-none d-sm-block updown">
                      <div class="p-relative">
                          <img class="tp-hero-med-plus" src="../assets-portal/img/medical/hero/shape-1.png" alt="">
                          <img class="tp-round-20" src="../assets-portal/img/medical/hero/afiez.jpg" alt="">
                          <span class="tp-ff-poppins tp-text-common-white fw-500 pt-10 d-inline-block pb-10">Dr. Stella</span>
                      </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-hero-med-area-end -->

     <!-- tp-capabili-area-start -->
     <div class="tp-capabili-area pt-115 pb-65">
        <div class="container container-1230">
           <div class="row">
              <div class="col-xl-6 col-lg-5" data-anime='{ "el": "childs", "rotateZ": [5, 0], "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
                 <div class="tp-capabili-content mr-100 mb-30">
                    <span class="tp-ff-poppins fs-17 fw-500 tp-text-theme-1 mb-20 d-block">Online video call opurtunity</span>
                    <span class="tp-capabili-border d-block mb-15"></span>
                    <h2 class="tp-ff-poppins fw-500 tp-text-common-black-4 ls-m-2"><a class="underline-black" href="#">Exceptional people and exceptional care</a></h2>
                 </div>
              </div>
              <div class="col-xl-6 col-lg-7">
                 <div class="row" data-anime='{ "el": "childs", "rotateZ": [5, 0], "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
                    <div class="col-lg-6 col-md-6">
                       <div class="tp-capabili-content mb-65">
                          <span class="tp-ff-poppins fs-17 fw-500 tp-text-theme-1 mb-20 d-block">Medical Treatment</span>
                          <span class="tp-capabili-border d-block mb-15"></span>
                          <h2 class="tp-ff-poppins fs-24 fw-500 tp-text-common-black-4 ls-m-2 mb-20"><a class="underline-black" href="#">Benefits of proactive
                             health management</a></h2>
                          <p class="tp-ff-roboto fs-17 lh-28 tp-text-grey-9 mr-30">Chesterton, a writer of paradox
                             on our  list. He speaks for the
                             must buck conven</p>
                       </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                       <div class="tp-capabili-content mb-65">
                          <span class="tp-ff-poppins fs-17 fw-500 tp-text-theme-1 mb-20 d-block">Medical Education</span>
                          <span class="tp-capabili-border d-block mb-15"></span>
                          <h2 class="tp-ff-poppins fs-24 fw-500 tp-text-common-black-4 ls-m-2 mb-20"><a class="underline-black" href="#">Access expert advice
                             a thriving life</a></h2>
                          <p class="tp-ff-roboto fs-17 lh-28 tp-text-grey-9 mr-30">Chesterton, a writer of paradox
                             on our  list. He speaks for the
                             must buck conven</p>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-capabili-area-end -->

     <!-- tp-about-area-start -->
     <div class="tp-about-area pb-110">
        <div class="container container-1230">
           <div class="row gx-24">
              <div class="col-xxl-7 col-xxl-6 col-lg-6">
                 <div class="tp-about-lwr-left tp-about-med-left mr-80 mb-30">
                    <div class="row">
                       <div class="col-lg-6 col-md-6 col-sm-6">
                          <div class="tp-about-lwr-thumb mr-30">
                             <img class="w-100 tp-round-10" data-parallax='{"y": -50, "smoothness": 20}' src="../assets-portal/img/medical/about/afiez.jpg" alt="">
                          </div>
                       </div>
                       <div class="col-lg-6 col-md-6 col-sm-6">
                          <div class="tp-about-lwr-thumb ml-25 mt-70">
                             <img class="w-100 tp-round-10" data-parallax='{"y": -50, "smoothness": 20}' src="../assets-portal/img/medical/about/afiez.jpg" alt="">
                          </div>
                       </div>
                    </div>
                    <div class="tp-about-lwr-thumb-3 ml-125 mr-175">
                       <img class="w-100 tp-round-10" data-parallax='{"y": 50, "smoothness": 20}' src="../assets-portal/img/medical/about/afiez.jpg" alt="about">
                    </div>
                 </div>
              </div>
              <div class="col-xxl-5 col-xl-6 col-lg-6">
                 <div class="tp-about-lwr-content mt-20 mb-20"  data-anime='{ "el": "childs", "translateX": [20, 0], "opacity": [0,1], "duration": 600, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <h2 class="tp-ff-poppins tp-text-common-black-4 fs-50 fs-lg-45 fw-600 ls-m-2 mb-20">Quality healthcare solution for all</h2>
                    <p class="tp-text-grey-9 tp-ff-roboto ls-0 lh-30 mb-30">Med always help to provide proper treatment for most of get the
                       proper cure  and healthy life which is the cooperate  focus
                       thing for us patient our  main propriety services</p>
                    <div class="tp-about-med-list mb-50">
                       <ul>
                          <li>
                             <div class="d-flex align-items-center dvdr mb-15">
                                <span class="icon rounded-circle text-center mr-15">
                                   <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M23.0063 21.0139H21.7273L20.4063 17.0649C20.2074 16.4684 19.8261 15.9495 19.3163 15.5815C18.8065 15.2134 18.194 15.0149 17.5653 15.0139H14.0063C13.4758 15.0139 12.9671 14.8031 12.592 14.4281C12.217 14.053 12.0063 13.5443 12.0063 13.0139H18.0063C18.2715 13.0139 18.5258 12.9085 18.7134 12.721C18.9009 12.5334 19.0063 12.2791 19.0063 12.0139C19.0063 11.7486 18.9009 11.4943 18.7134 11.3067C18.5258 11.1192 18.2715 11.0139 18.0063 11.0139H12.0063V8.01385C12.0002 7.96864 11.9912 7.92387 11.9793 7.87985C12.9259 7.64245 13.7531 7.06713 14.3051 6.26224C14.8571 5.45735 15.0957 4.47842 14.9761 3.50981C14.8565 2.54119 14.3869 1.64972 13.6557 1.00327C12.9245 0.356821 11.9822 0 11.0063 0C10.0303 0 9.08798 0.356821 8.3568 1.00327C7.62561 1.64972 7.15601 2.54119 7.0364 3.50981C6.9168 4.47842 7.15545 5.45735 7.70742 6.26224C8.25939 7.06713 9.08659 7.64245 10.0333 7.87985C10.0213 7.92387 10.0123 7.96864 10.0063 8.01385V13.0139C10.0083 14.0579 10.4186 15.0598 11.1493 15.8055C11.8801 16.5512 12.8734 16.9816 13.9173 17.0049C13.7345 18.0978 13.2526 19.1187 12.5251 19.9545C11.7976 20.7903 10.8528 21.4083 9.79551 21.74C8.73823 22.0717 7.60975 22.1042 6.53516 21.8338C5.46058 21.5633 4.48187 21.0006 3.70753 20.208C2.9332 19.4153 2.39349 18.4237 2.14824 17.3431C1.90299 16.2625 1.96177 15.1351 2.31808 14.0859C2.67439 13.0366 3.3143 12.1065 4.16687 11.3987C5.01944 10.6909 6.05137 10.2331 7.14825 10.0759C7.41095 10.0382 7.64792 9.89772 7.80705 9.68534C7.96617 9.47295 8.03441 9.20605 7.99675 8.94335C7.95909 8.68066 7.81862 8.44368 7.60623 8.28456C7.39385 8.12543 7.12695 8.05719 6.86425 8.09486C5.38822 8.30655 4.00073 8.92656 2.85835 9.88492C1.71597 10.8433 0.864176 12.1019 0.399087 13.5186C-0.066002 14.9354 -0.125873 16.4539 0.226229 17.9029C0.578331 19.3518 1.32839 20.6736 2.39177 21.7189C3.45515 22.7642 4.78952 23.4915 6.2443 23.8188C7.69909 24.146 9.21638 24.0602 10.625 23.5709C12.0335 23.0816 13.2773 22.2084 14.216 21.0498C15.1547 19.8912 15.7509 18.4933 15.9373 17.0139H17.5653C17.7752 17.0138 17.9799 17.0798 18.1502 17.2026C18.3205 17.3254 18.4479 17.4986 18.5143 17.6979L20.0583 22.3299C20.1245 22.5289 20.2518 22.7021 20.4219 22.8248C20.592 22.9476 20.7965 23.0137 21.0063 23.0139H23.0063C23.2715 23.0139 23.5258 22.9085 23.7134 22.721C23.9009 22.5334 24.0063 22.2791 24.0063 22.0139C24.0063 21.7486 23.9009 21.4943 23.7134 21.3067C23.5258 21.1192 23.2715 21.0139 23.0063 21.0139ZM9.00625 4.01385C9.00625 3.61829 9.12355 3.23161 9.34331 2.90271C9.56308 2.57382 9.87543 2.31747 10.2409 2.1661C10.6063 2.01472 11.0085 1.97511 11.3964 2.05228C11.7844 2.12945 12.1408 2.31994 12.4205 2.59964C12.7002 2.87935 12.8907 3.23571 12.9678 3.62367C13.045 4.01164 13.0054 4.41377 12.854 4.77922C12.7026 5.14467 12.4463 5.45703 12.1174 5.67679C11.7885 5.89656 11.4018 6.01385 11.0063 6.01385C10.4758 6.01385 9.96711 5.80314 9.59204 5.42807C9.21697 5.053 9.00625 4.54429 9.00625 4.01385Z" fill="#6C60DC" />
                                   </svg>
                                </span>
                                <h5 class="tp-ff-poppins fw-500 fs-18 ls-m-2 tp-text-common-black-4 lh-22">Modern instrument</h5>
                             </div>
                             <p class="tp-text-grey-9 tp-ff-roboto ls-0 lh-24">Chesterton of paradoical<br>
                                He speaks headed</p>
                          </li>
                          <li>
                             <div class="d-flex align-items-center dvdr">
                                <span class="icon rounded-circle text-center mr-15">
                                   <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M17.95 5.53603L14.464 2.05002C13.8156 1.3981 13.0443 0.881248 12.1948 0.529368C11.3453 0.177488 10.4345 -0.00243056 9.515 2.47962e-05H5C3.67441 0.00161265 2.40356 0.528907 1.46622 1.46624C0.528882 2.40358 0.00158786 3.67443 0 5.00003V19C0.00158786 20.3256 0.528882 21.5965 1.46622 22.5338C2.40356 23.4711 3.67441 23.9984 5 24H15C16.3256 23.9984 17.5964 23.4711 18.5338 22.5338C19.4711 21.5965 19.9984 20.3256 20 19V10.485C20.0025 9.56554 19.8225 8.6547 19.4707 7.80521C19.1188 6.95572 18.6019 6.18445 17.95 5.53603ZM16.536 6.95003C16.841 7.26443 17.1036 7.61744 17.317 8.00003H13C12.7348 8.00003 12.4804 7.89467 12.2929 7.70713C12.1054 7.5196 12 7.26524 12 7.00003V2.68303C12.3826 2.89641 12.7356 3.15898 13.05 3.46403L16.536 6.95003ZM18 19C18 19.7957 17.6839 20.5587 17.1213 21.1213C16.5587 21.684 15.7956 22 15 22H5C4.20435 22 3.44129 21.684 2.87868 21.1213C2.31607 20.5587 2 19.7957 2 19V5.00003C2 4.20438 2.31607 3.44131 2.87868 2.8787C3.44129 2.3161 4.20435 2.00003 5 2.00003H9.515C9.68 2.00003 9.838 2.03202 10 2.04702V7.00003C10 7.79568 10.3161 8.55874 10.8787 9.12135C11.4413 9.68396 12.2044 10 13 10H17.953C17.968 10.162 18 10.32 18 10.485V19ZM14.724 13.311C14.9066 13.5031 15.0055 13.7598 14.9989 14.0248C14.9924 14.2897 14.8809 14.5412 14.689 14.724L11.1 18.138C10.5358 18.6933 9.77508 19.0031 8.9835 18.9999C8.19192 18.9967 7.43367 18.6808 6.874 18.121L5.334 16.747C5.13588 16.5704 5.01605 16.3223 5.00086 16.0573C4.99334 15.9261 5.01173 15.7947 5.055 15.6706C5.09826 15.5465 5.16554 15.4321 5.253 15.334C5.34046 15.2359 5.44639 15.156 5.56473 15.0989C5.68307 15.0417 5.81151 15.0084 5.94272 15.0009C6.20771 14.9857 6.46788 15.0764 6.666 15.253L8.251 16.667C8.34174 16.7689 8.45236 16.8512 8.57608 16.9087C8.69979 16.9663 8.83397 16.9979 8.97036 17.0016C9.10675 17.0054 9.24246 16.9812 9.36914 16.9305C9.49583 16.8798 9.61081 16.8038 9.707 16.707L13.307 13.276C13.4022 13.185 13.5144 13.1137 13.6373 13.0662C13.7601 13.0186 13.8911 12.9959 14.0228 12.9991C14.1544 13.0024 14.2842 13.0316 14.4045 13.0851C14.5248 13.1386 14.6334 13.2154 14.724 13.311Z" fill="#6C60DC" />
                                   </svg>
                                </span>
                                <h5 class="tp-ff-poppins fw-500 fs-18 ls-m-2 tp-text-common-black-4 lh-22">Easy billing systems</h5>
                             </div>
                             <p class="tp-text-grey-9 tp-ff-roboto ls-0 lh-24">Chesterton of paradoical<br>
                                He speaks headed</p>
                          </li>
                       </ul>
                    </div>
                    <div class="tp-about-med-btn-wrap d-flex flex-wrap">
                       <a href="about.html" class="tp-btn-switch-text mb-10 btn-lg mr-20 bg-hover-black p-relative tp-round-100 d-inline-block hover-text-white tp-bg-theme-1 tp-text-common-white fw-500 tp-ff-poppins lh-26 ls-m-2">
                          <span>
                             <span class="btn-double-text" data-text="About Hospital">About Hospital</span>
                          </span>
                       </a>
                       <a href="timetable.html" class="tp-btn-switch-text mb-10 tp-btn-med-border btn-lg bg-hover-theme-1 p-relative tp-round-100 d-inline-block hover-text-white tp-bg-common-white tp-text-common-black-4 fw-500 tp-ff-poppins lh-26 ls-m-2">
                          <span>
                             <span class="mr-5">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M13.8333 8.83333C13.8333 12.0533 11.22 14.6667 7.99999 14.6667C4.77999 14.6667 2.16666 12.0533 2.16666 8.83333C2.16666 5.61333 4.77999 3 7.99999 3C11.22 3 13.8333 5.61333 13.8333 8.83333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                   <path d="M8 5.33331V8.66665" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                   <path d="M6 1.33331H10" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                             </span>
                             <span class="btn-double-text" data-text="Check Timetable">Check Timetable</span>
                          </span>
                       </a>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-about-area-end -->

     <!-- tp-work-area-start -->
     <div class="tp-work-area tp-bg-grey-10 pt-135 pb-120 z-index-1 p-relative">
        <img class="tp-work-med-shape p-absolute z-index-m-1" src="../assets-portal/img/medical/work/shape.png" alt="">
        <div class="container container-1230">
           <div class="row gx-24 align-items-end mb-30" data-anime='{ "el": "childs", "rotateZ": [5, 0], "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
              <div class="col-lg-8">
                 <div class="tp-work-title-wrap mb-25">
                    <span class="tp-ff-poppins fw-600 tp-text-theme-1 d-inline-block mb-15 lh-1">OUR EXCLUSIVE SERVICE</span>
                    <h2 class="tp-ff-poppins tp-text-common-black-4 fs-50 fw-600 ls-m-2 lh-110-per">Our clinic treatments</h2>
                 </div>
              </div>
              <div class="col-lg-4">
                 <div class="tp-work-med-btn-wrap text-end mb-30">
                    <a class="tp-work-med-btn fs-16 fw-500  ls-m-2 lh-26 tp-text-theme-1" href="treatments.html">All Treatments</a>
                 </div>
              </div>
           </div>
           <div class="row gx-0 tp-work-lwr-border" data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 800, "delay": 200, "staggervalue": 300, "easing": "easeOutQuad" }'>
              <div class="col-lg-4 col-md-6">
                 <div class="tp-work-lwr-item tp-team-med-item tp-img-hover text-center mb-30">
                    <div class="fix lwr-shadow tp-round-6 mb-35  p-relative">
                       <img class="w-100 tp-round-6 tp-img-hover-inner-up" src="../assets-portal/img/medical/work/afiez.jpg" alt="work">
                       <span class="tp-team-med-overly"></span>
                    </div>
                    <div class="tp-work-lwr-conent">
                       <h4 class="tp-ff-poppins ls-m-2 fw-500 fs-26 tp-text-common-black-4"><a class="underline-lwr" href="treatments.html">General surgery</a></h4>
                       <p class="tp-text-grey-9 tp-ff-roboto mb-25">Lorem ipsum dolor sit amet. Ut inventore
                          consequatur eos nobisa utem sit</p>
                       <a href="treatments.html" class="tp-btn-switch-animation ml-5 d-inline-block fw-500 tp-text-common-black-4 hover-text-black tp-ff-poppins ls-m-2 ">
                          <span class="d-flex align-items-center justify-content-center">
                             <span class="btn-text"> Know More</span>
                             <span class="btn-icon"><i class="fa-sharp fa-solid fa-angle-right"></i></span>
                             <span class="btn-icon"><i class="fa-sharp fa-solid fa-angle-right"></i></span>
                          </span>
                       </a>
                    </div>
                 </div>
              </div>
              <div class="col-lg-4 col-md-6">
                 <div class="tp-work-lwr-item tp-team-med-item tp-img-hover text-center mb-30">
                    <div class="fix lwr-shadow tp-round-6 mb-35 p-relative">
                       <img class="w-100 tp-round-6 tp-img-hover-inner-up" src="../assets-portal/img/medical/work/afiez.jpg" alt="work">
                       <span class="tp-team-med-overly"></span>
                    </div>
                    <div class="tp-work-lwr-conent">
                       <h4 class="tp-ff-poppins ls-m-2 fw-500 fs-26 tp-text-common-black-4"><a class="underline-lwr" href="treatments.html">Cardiology</a></h4>
                       <p class="tp-text-grey-9 tp-ff-roboto mb-25">Lorem ipsum dolor sit amet. Ut inventore
                          consequatur eos nobisa utem sit</p>
                       <a href="treatments.html" class="tp-btn-switch-animation ml-5 d-inline-block fw-500 tp-text-common-black-4 hover-text-black tp-ff-poppins ls-m-2">
                          <span class="d-flex align-items-center justify-content-center">
                             <span class="btn-text"> Know More</span>
                             <span class="btn-icon"><i class="fa-sharp fa-solid fa-angle-right"></i></span>
                             <span class="btn-icon"><i class="fa-sharp fa-solid fa-angle-right"></i></span>
                          </span>
                       </a>
                    </div>
                 </div>
              </div>
              <div class="col-lg-4 col-md-6">
                 <div class="tp-work-lwr-item  tp-team-med-item tp-img-hover text-center mb-30">
                    <div class="fix lwr-shadow tp-round-6 mb-35 p-relative">
                       <img class="w-100 tp-round-6 tp-img-hover-inner-up" src="../assets-portal/img/medical/work/afiez.jpg" alt="work">
                       <span class="tp-team-med-overly"></span>
                    </div>
                    <div class="tp-work-lwr-conent">
                       <h4 class="tp-ff-poppins ls-m-2 fw-500 fs-26 tp-text-common-black-4"><a class="underline-lwr" href="treatments.html">Primary care</a></h4>
                       <p class="tp-text-grey-9 tp-ff-roboto mb-25">Lorem ipsum dolor sit amet. Ut inventore
                          consequatur eos nobisa utem sit</p>
                       <a href="treatments.html" class="tp-btn-switch-animation ml-5 d-inline-block fw-500 tp-text-common-black-4 hover-text-black tp-ff-poppins ls-m-2">
                          <span class="d-flex align-items-center justify-content-center">
                             <span class="btn-text"> Know More</span>
                             <span class="btn-icon"><i class="fa-sharp fa-solid fa-angle-right"></i></span>
                             <span class="btn-icon"><i class="fa-sharp fa-solid fa-angle-right"></i></span>
                          </span>
                       </a>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-work-area-end -->

     <!-- tp-chose-area-start -->
     <div class="tp-chose-area pt-135 pb-70 p-relative">
        <img class="tp-chose-med-shape p-absolute z-index-10 updown" src="../assets-portal/img/medical/chose/ballon.png" alt="">
        <div class="container-1230 container">
           <div class="row gx-24">
              <div class="col-lg-6">
                 <div class="tp-chose-med-thumb tp-round-10 p-relative ml-80 mr-20 mb-30">
                    <img class="w-100 tp-round-10 thumb" src="../assets-portal/img/medical/chose/afiez.jpg" alt="thumb">
                    <div class="tp-expert-video tp-round-10 tp-bg-common-white text-center lh-125-per" data-parallax='{"y": 150, "smoothness": 20}'>
                       <div class="p-relative d-inline-block mb-20">
                          <img class="w-100 tp-round-10" src="../assets-portal/img/medical/chose/afiez.jpg" alt="thumb">
                          <a class="tp-btn-video tp-expert-play-video popup-video d-inline-block text-center mr-15"
                             href="https://www.youtube.com/watch?v=go7QYaQR494">
                             <span class="tp-text-theme-1 fs-12"><i class="fa-sharp fa-solid fa-play"></i></span>
                          </a>
                       </div>
                       <h3 class="tp-text-common-black-4 tp-ff-roboto lh-20 fs-16 pb-15 fw-400">Watch our<br> video</h3>
                    </div>
                 </div>
              </div>
              <div class="col-lg-6">
                 <div class="tp-chose-med-content mb-15 mr-55 ml-55" data-anime='{ "el": "childs", "translateX": [20, 0], "opacity": [0,1], "duration": 600, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <span class="tp-ff-poppins fw-600 tp-text-theme-1 d-inline-block mb-15 lh-1">THE NO 1 HOSPITAL</span>
                    <h2 class="tp-ff-poppins tp-text-common-black-4 fs-50 fs-lg-45 fs-xs-35 fw-600 ls-m-2 lh-110-per mb-20">Access our range of medical services</h2>
                    <p class="tp-text-grey-9 tp-ff-roboto ls-0 lh-30 mb-30">Med always help to provide proper treatment for most of get the
                       proper cure  and healthy life which is the cooperate  focus
                       thing for us patient our  main propriety services</p>
                    <div class="tp-about-cpr-feature-list mb-50">
                       <ul>
                          <li>
                             <span>
                                <svg width="11" height="9" viewBox="0 0 11 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M9.54528 1.27356L3.92028 7.04896L1.36346 4.42378" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                             </span>
                             Compassionate care for patients
                          </li>
                          <li>
                             <span>
                                <svg width="11" height="9" viewBox="0 0 11 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M9.54528 1.27356L3.92028 7.04896L1.36346 4.42378" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                             </span>
                             24/7 nurse staff available here
                          </li>
                       </ul>
                    </div>
                    <div class="tp-chose-med-btn-wrap">
                       <a href="tel:1234564890" class="tp-btn-switch-text btn-lg mr-20 bg-hover-black p-relative tp-round-100 d-inline-block hover-text-white tp-bg-theme-1 tp-text-common-white fw-500 tp-ff-poppins lh-26 ls-m-2">
                          <span>
                             <span class="mr-5">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M16.4775 13.7474C16.4775 14.0174 16.4175 14.2949 16.29 14.5649C16.1625 14.8349 15.9975 15.0899 15.78 15.3299C15.4125 15.7349 15.0075 16.0274 14.55 16.2149C14.1 16.4024 13.6125 16.4999 13.0875 16.4999C12.3225 16.4999 11.505 16.3199 10.6425 15.9524C9.77996 15.5849 8.91746 15.0899 8.06246 14.4674C7.19996 13.8374 6.38246 13.1399 5.60246 12.3674C4.82996 11.5874 4.13246 10.7699 3.50996 9.91488C2.89496 9.05988 2.39996 8.20488 2.03996 7.35738C1.67996 6.50238 1.49996 5.68488 1.49996 4.90488C1.49996 4.39488 1.58996 3.90738 1.76996 3.45738C1.94996 2.99988 2.23496 2.57988 2.63246 2.20488C3.11246 1.73238 3.63746 1.49988 4.19246 1.49988C4.40246 1.49988 4.61246 1.54488 4.79996 1.63488C4.99496 1.72488 5.16746 1.85988 5.30246 2.05488L7.04246 4.50738C7.17746 4.69488 7.27496 4.86738 7.34246 5.03238C7.40996 5.18988 7.44746 5.34738 7.44746 5.48988C7.44746 5.66988 7.39496 5.84988 7.28996 6.02238C7.19246 6.19488 7.04996 6.37488 6.86996 6.55488L6.29996 7.14738C6.21746 7.22988 6.17996 7.32738 6.17996 7.44738C6.17996 7.50738 6.18746 7.55988 6.20246 7.61988C6.22496 7.67988 6.24746 7.72488 6.26246 7.76988C6.39746 8.01738 6.62996 8.33988 6.95996 8.72988C7.29746 9.11988 7.65746 9.51738 8.04746 9.91488C8.45246 10.3124 8.84246 10.6799 9.23996 11.0174C9.62996 11.3474 9.95246 11.5724 10.2075 11.7074C10.245 11.7224 10.29 11.7449 10.3425 11.7674C10.4025 11.7899 10.4625 11.7974 10.53 11.7974C10.6575 11.7974 10.755 11.7524 10.8375 11.6699L11.4075 11.1074C11.595 10.9199 11.775 10.7774 11.9475 10.6874C12.12 10.5824 12.2925 10.5299 12.48 10.5299C12.6225 10.5299 12.7725 10.5599 12.9375 10.6274C13.1025 10.6949 13.275 10.7924 13.4625 10.9199L15.945 12.6824C16.14 12.8174 16.275 12.9749 16.3575 13.1624C16.4325 13.3499 16.4775 13.5374 16.4775 13.7474Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                                   <path d="M12.15 5.85012H15.75M12.15 5.85012V2.25012V5.85012Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                             </span>
                             <span class="btn-double-text" data-text="123 456 4890">123 456 4890</span>
                          </span>
                       </a>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-chose-area-end -->

     <!-- tp-counter-area-start -->
     <div class="tp-counter-area pb-80">
        <div class="container-1230 container">
           <div class="row gx-24">
              <div class="col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-counter-med-item counter-style-01 text-center">
                    <div class="d-inline-block p-relative">
                       <span class="vertical-counter lh-1 d-inline-flex mb-5 p-relative z-index-1 tp-text-common-black-4 fw-600 tp-ff-poppins fs-80 fs-sm-50 ls-m-4" data-text="+" data-to="594"></span>
                       <div class="tp-funfact-digi-border"></div>
                    </div>
                    <p class="tp-text-grey-9 tp-ff-poppins ls-m-1 fs-18 fw-500 mb-55">Worldwide branches</p>
                 </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-counter-med-item counter-style-01 text-center">
                    <div class="d-inline-block p-relative">
                       <span class="lh-1 vertical-counter d-inline-flex mb-5 p-relative z-index-1 tp-text-common-black-4 fw-600 tp-ff-poppins fs-80 fs-sm-50 ls-m-4" data-text="%" data-to="98"></span>
                       <div class="tp-funfact-digi-border"></div>
                    </div>
                    <p class="tp-text-grey-9 tp-ff-poppins ls-m-1 fs-18 fw-500 mb-55">Genuine clients feedback</p>
                 </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-counter-med-item counter-style-01 text-center">
                    <div class="d-inline-block p-relative">
                       <span class="lh-1 vertical-counter d-inline-flex mb-5 p-relative z-index-1 tp-text-common-black-4 fw-600 tp-ff-poppins fs-80 fs-sm-50 ls-m-4" data-text="+" data-to="374"></span>
                       <div class="tp-funfact-digi-border"></div>
                    </div>
                    <p class="tp-text-grey-9 tp-ff-poppins ls-m-1 fs-18 fw-500 mb-55">Daily patient consulted</p>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-counter-area-end -->

     <!-- tp-contact-area-start -->
     <div class="tp-contact-area p-relative">
        <div class="fix">
           <div class="container-fluid p-0">
              <div class="row">
                 <div class="col-12">
                    <div class="swiper-container tp-contact-med-slide-active">
                       <div class="swiper-wrapper">
                          <div class="swiper-slide">
                             <div class="tp-contact-med-thumb tp-img-hover fix p-relative tp-round-10">
                                <img class="w-100 tp-round-10 tp-img-hover-inner" src="../assets-portal/img/medical/contact/afiez.jpg" alt="contact">
                             </div>
                          </div>
                          <div class="swiper-slide">
                             <div class="tp-contact-med-thumb  tp-img-hover fix p-relative tp-round-10">
                                <img class="w-100 tp-round-10 tp-img-hover-inner" src="../assets-portal/img/medical/contact/afiez.jpg" alt="contact">
                             </div>
                          </div>
                          <div class="swiper-slide">
                             <div class="tp-contact-med-thumb tp-img-hover fix p-relative tp-round-10">
                                <img class="w-100 tp-round-10 tp-img-hover-inner" src="../assets-portal/img/medical/contact/afiez.jpg" alt="contact">
                             </div>
                          </div>
                          <div class="swiper-slide">
                             <div class="tp-contact-med-thumb tp-img-hover fix p-relative tp-round-10">
                                <img class="w-100 tp-round-10 tp-img-hover-inner" src="../assets-portal/img/medical/contact/afiez.jpg" alt="contact">
                             </div>
                          </div>
                          <div class="swiper-slide">
                             <div class="tp-contact-med-thumb tp-img-hover fix p-relative tp-round-10">
                                <img class="w-100 tp-round-10 tp-img-hover-inner" src="../assets-portal/img/medical/contact/afiez.jpg" alt="contact">
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <div class="tp-service-cpr-contact tp-contact-med-form text-center tp-round-10">
           <h4 class="tp-ff-poppins fw-600 fs-20 ls-m-2 tp-text-common-black-4 mb-25">Easy way to contact</h4>
           <div class="tp-footer-cpr-form">
              <form action="#">
                 <input class="input mb-10" type="text" placeholder="Patient’s name">
                 <input class="input mb-10" type="email" placeholder="Patient’s email">
                 <select class="select mb-10" name="doctor">
                    <option value="1">Select doctor</option>
                    <option value="2">Saab</option>
                    <option value="3">Mercedes</option>
                    <option value="4">Audi</option>
                 </select>
                 <textarea class="textarea mb-5" placeholder="Your message"></textarea>
                 <button type="submit" class="tp-btn-switch-text tp-btn-med-md w-100 bg-hover-black p-relative tp-round-6 d-inline-block hover-text-white tp-bg-theme-1 tp-text-common-white fw-500 fs-14 tp-ff-poppins lh-26 ls-m-2">
                    <span>
                       <span class="mr-5 tp-transfrom">
                          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                             <path d="M5.25 12.8333H8.75C11.6667 12.8333 12.8333 11.6666 12.8333 8.74996V5.24996C12.8333 2.33329 11.6667 1.16663 8.75 1.16663H5.25C2.33334 1.16663 1.16667 2.33329 1.16667 5.24996V8.74996C1.16667 11.6666 2.33334 12.8333 5.25 12.8333Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                             <path d="M4.52083 6.99995L6.17166 8.65079L9.47916 5.34912" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                       </span>
                       <span class="btn-double-text" data-text="Book Appointment">Book Appointment</span>
                    </span>
                 </button>
              </form>
           </div>
       </div>
     </div>
     <!-- tp-contact-area-end -->

     <!-- tp-feature-area-start -->
     <div class="tp-feature-area pt-100" data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 150, "easing": "easeOutQuad" }'>
        <div class="container-1230 container">
           <div class="tp-feature-med-wrap tp-round-100 d-flex flex-wrap justify-content-between">
              <div class="tp-feature-med-content d-flex align-items-center mb-20 mr-20">
                 <span class="icon d-inline-block mr-20 rounded-circle tp-text-theme-1 tp-bg-common-white text-center">
                    <svg width="38" height="36" viewBox="0 0 38 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <path d="M32.7131 5.15113C32.1618 3.6426 31.1607 2.33968 29.845 1.41845C28.5294 0.497226 26.9626 0.00210356 25.3565 0H12.2087C10.6026 0.00210356 9.03586 0.497226 7.72022 1.41845C6.40458 2.33968 5.40347 3.6426 4.85218 5.15113L0.626094 16.776C0.21045 17.9209 -0.00144741 19.1298 7.44083e-06 20.3478V21.913C0.0028003 23.1226 0.287119 24.315 0.830457 25.3957C1.37379 26.4764 2.16123 27.4158 3.13044 28.1395C3.13044 28.152 3.13044 28.1614 3.13044 28.1739V31.3043C3.13044 32.5497 3.62516 33.7441 4.50577 34.6247C5.38637 35.5053 6.58073 36 7.8261 36C9.07146 36 10.2658 35.5053 11.1464 34.6247C12.027 33.7441 12.5217 32.5497 12.5217 31.3043V29.7391H25.0435V31.3043C25.0435 32.5497 25.5382 33.7441 26.4188 34.6247C27.2994 35.5053 28.4938 36 29.7391 36C30.9845 36 32.1789 35.5053 33.0595 34.6247C33.9401 33.7441 34.4348 32.5497 34.4348 31.3043V28.1739C34.4348 28.1614 34.4348 28.152 34.4348 28.1395C35.404 27.4158 36.1914 26.4764 36.7348 25.3957C37.2781 24.315 37.5624 23.1226 37.5652 21.913V20.3478C37.5668 19.1303 37.3549 17.9219 36.9391 16.7776L32.7131 5.15113ZM7.79792 6.22174C8.12887 5.31731 8.72913 4.53607 9.51779 3.98333C10.3065 3.43059 11.2456 3.13292 12.2087 3.13043H25.3565C26.3201 3.13228 27.26 3.42966 28.0493 3.98244C28.8386 4.53522 29.4393 5.31681 29.7704 6.22174L33.1951 15.6522H4.36853L7.79792 6.22174ZM9.39131 31.3043C9.39131 31.7195 9.22641 32.1176 8.93287 32.4111C8.63934 32.7047 8.24122 32.8696 7.8261 32.8696C7.41097 32.8696 7.01285 32.7047 6.71932 32.4111C6.42578 32.1176 6.26088 31.7195 6.26088 31.3043V29.5826C6.77604 29.6872 7.30042 29.7397 7.8261 29.7391H9.39131V31.3043ZM31.3044 31.3043C31.3044 31.7195 31.1395 32.1176 30.8459 32.4111C30.5524 32.7047 30.1543 32.8696 29.7391 32.8696C29.324 32.8696 28.9259 32.7047 28.6324 32.4111C28.3388 32.1176 28.1739 31.7195 28.1739 31.3043V29.7391H29.7391C30.2648 29.7397 30.7892 29.6872 31.3044 29.5826V31.3043ZM34.4348 21.913C34.4348 23.1584 33.9401 24.3528 33.0595 25.2334C32.1789 26.114 30.9845 26.6087 29.7391 26.6087H7.8261C6.58073 26.6087 5.38637 26.114 4.50577 25.2334C3.62516 24.3528 3.13044 23.1584 3.13044 21.913V20.3478C3.13682 19.8208 3.20032 19.296 3.31983 18.7826H6.26088V20.3478C6.26088 20.7629 6.42578 21.1611 6.71932 21.4546C7.01285 21.7481 7.41097 21.913 7.8261 21.913C8.24122 21.913 8.63934 21.7481 8.93287 21.4546C9.22641 21.1611 9.39131 20.7629 9.39131 20.3478V18.7826H28.1739V20.3478C28.1739 20.7629 28.3388 21.1611 28.6324 21.4546C28.9259 21.7481 29.324 21.913 29.7391 21.913C30.1543 21.913 30.5524 21.7481 30.8459 21.4546C31.1395 21.1611 31.3044 20.7629 31.3044 20.3478V18.7826H34.2454C34.365 19.296 34.4285 19.8208 34.4348 20.3478V21.913Z" fill="currentColor" />
                    </svg>
                 </span>
                 <div>
                    <h3 class="tp-ff-poppins fw-500 fs-22 ls-m-2 tp-text-common-black-4 mb-5">Emergency service</h3>
                    <span class="tp-ff-roboto fs-17 tp-text-grey-9 ls-m-0">24/7 hours emergency service</span>
                 </div>
              </div>
              <div class="tp-feature-med-content d-flex align-items-center mb-20 mr-20">
                 <span class="icon d-inline-block mr-20 rounded-circle tp-text-theme-1 tp-bg-common-white text-center">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <path d="M36 13.5C36.0034 12.6742 35.7795 11.8634 35.3529 11.1564C34.9262 10.4494 34.3133 9.87338 33.5811 9.49149C32.8489 9.1096 32.0258 8.93654 31.2018 8.99127C30.3778 9.04599 29.5848 9.32639 28.9096 9.80176C28.2344 10.2771 27.7029 10.9291 27.3735 11.6864C27.0441 12.4436 26.9295 13.2769 27.0421 14.095C27.1547 14.913 27.4902 15.6844 28.0119 16.3244C28.5336 16.9645 29.2214 17.4487 30 17.724V24C30 26.3869 29.0518 28.6761 27.364 30.364C25.6761 32.0518 23.3869 33 21 33C18.6131 33 16.3239 32.0518 14.636 30.364C12.9482 28.6761 12 26.3869 12 24V23.88C14.4961 23.5178 16.7787 22.27 18.431 20.3643C20.0834 18.4586 20.9952 16.0223 21 13.5V7.5C20.9976 5.51161 20.2067 3.60534 18.8007 2.19933C17.3947 0.793323 15.4884 0.00238179 13.5 0C13.1022 0 12.7206 0.158035 12.4393 0.43934C12.158 0.720644 12 1.10218 12 1.5C12 1.89782 12.158 2.27936 12.4393 2.56066C12.7206 2.84196 13.1022 3 13.5 3C14.6935 3 15.8381 3.47411 16.682 4.31802C17.5259 5.16193 18 6.30653 18 7.5V13.5C18 15.4891 17.2098 17.3968 15.8033 18.8033C14.3968 20.2098 12.4891 21 10.5 21C8.51088 21 6.60322 20.2098 5.1967 18.8033C3.79018 17.3968 3 15.4891 3 13.5V7.5C3 6.30653 3.47411 5.16193 4.31802 4.31802C5.16193 3.47411 6.30653 3 7.5 3C7.89782 3 8.27936 2.84196 8.56066 2.56066C8.84196 2.27936 9 1.89782 9 1.5C9 1.10218 8.84196 0.720644 8.56066 0.43934C8.27936 0.158035 7.89782 0 7.5 0C5.51161 0.00238179 3.60534 0.793323 2.19933 2.19933C0.793323 3.60534 0.00238179 5.51161 0 7.5L0 13.5C0.00482393 16.0223 0.916637 18.4586 2.56896 20.3643C4.22128 22.27 6.50388 23.5178 9 23.88V24C9 27.1826 10.2643 30.2348 12.5147 32.4853C14.7652 34.7357 17.8174 36 21 36C24.1826 36 27.2348 34.7357 29.4853 32.4853C31.7357 30.2348 33 27.1826 33 24V17.724C33.8744 17.4149 34.6319 16.8432 35.1689 16.087C35.7059 15.3309 35.9962 14.4274 36 13.5ZM31.5 15C31.2033 15 30.9133 14.912 30.6666 14.7472C30.42 14.5824 30.2277 14.3481 30.1142 14.074C30.0006 13.7999 29.9709 13.4983 30.0288 13.2074C30.0867 12.9164 30.2296 12.6491 30.4393 12.4393C30.6491 12.2296 30.9164 12.0867 31.2074 12.0288C31.4983 11.9709 31.7999 12.0006 32.074 12.1142C32.3481 12.2277 32.5824 12.42 32.7472 12.6666C32.912 12.9133 33 13.2033 33 13.5C33 13.8978 32.842 14.2794 32.5607 14.5607C32.2794 14.842 31.8978 15 31.5 15Z" fill="currentColor" />
                    </svg>
                 </span>
                 <div>
                    <h3 class="tp-ff-poppins fw-500 fs-22 ls-m-2 tp-text-common-black-4 mb-5">Qualified doctors</h3>
                    <span class="tp-ff-roboto fs-17 tp-text-grey-9 ls-m-0">World best doctor collections</span>
                 </div>
              </div>
              <div class="tp-feature-med-content d-flex align-items-center mb-20">
                 <span class="icon d-inline-block mr-20 rounded-circle tp-text-theme-1 tp-bg-common-white text-center">
                    <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <path d="M24.8711 3.21031L15.4738 0.07686C15.1661 -0.02562 14.8335 -0.02562 14.5258 0.07686L5.12842 3.21031C3.6343 3.70663 2.33459 4.66118 1.41395 5.93834C0.493303 7.2155 -0.00144249 8.75031 3.15913e-06 10.3247V18.0001C3.15913e-06 29.3444 13.7998 35.6098 14.3908 35.8708C14.5825 35.956 14.79 36 14.9998 36C15.2096 36 15.417 35.956 15.6088 35.8708C16.1998 35.6098 29.9995 29.3444 29.9995 18.0001V10.3247C30.001 8.75031 29.5062 7.2155 28.5856 5.93834C27.665 4.66118 26.3652 3.70663 24.8711 3.21031ZM26.9996 18.0001C26.9996 26.1825 17.5212 31.5494 14.9998 32.8334C12.4753 31.5539 2.99996 26.2035 2.99996 18.0001V10.3247C3.00004 9.38013 3.29736 8.45954 3.84979 7.69336C4.40222 6.92717 5.18174 6.35425 6.07791 6.05577L14.9998 3.08131L23.9216 6.05577C24.8178 6.35425 25.5973 6.92717 26.1498 7.69336C26.7022 8.45954 26.9995 9.38013 26.9996 10.3247V18.0001Z" fill="currentColor" />
                       <path d="M19.9494 12.4495L13.6675 18.7494L10.3015 15.2395C10.1664 15.0918 10.0032 14.9726 9.82132 14.889C9.63948 14.8054 9.44274 14.759 9.24269 14.7526C9.04264 14.7462 8.84333 14.7799 8.65651 14.8517C8.46968 14.9235 8.29912 15.032 8.15487 15.1707C8.01061 15.3095 7.89559 15.4757 7.81656 15.6596C7.73754 15.8435 7.69612 16.0413 7.69474 16.2415C7.69336 16.4416 7.73205 16.64 7.80853 16.825C7.88501 17.0099 7.99773 17.1777 8.14006 17.3184L11.599 20.9184C11.857 21.197 12.1688 21.4204 12.5155 21.5751C12.8623 21.7298 13.2368 21.8126 13.6165 21.8184H13.666C14.0381 21.8196 14.4067 21.7469 14.7504 21.6045C15.0942 21.4621 15.4062 21.2529 15.6684 20.9889L22.0763 14.581C22.2163 14.4413 22.3274 14.2755 22.4032 14.0929C22.4791 13.9103 22.5182 13.7145 22.5184 13.5168C22.5187 13.3191 22.4799 13.1233 22.4045 12.9405C22.329 12.7578 22.2183 12.5917 22.0786 12.4517C21.9389 12.3118 21.7731 12.2007 21.5905 12.1249C21.4079 12.049 21.2122 12.0099 21.0145 12.0096C20.8167 12.0094 20.6209 12.0482 20.4382 12.1236C20.2554 12.1991 20.0893 12.3098 19.9494 12.4495Z" fill="currentColor" />
                    </svg>
                 </span>
                 <div>
                    <h3 class="tp-ff-poppins fw-500 fs-22 ls-m-2 tp-text-common-black-4 mb-5">Online report</h3>
                    <span class="tp-ff-roboto fs-17 tp-text-grey-9 ls-m-0">We mail your report</span>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-feature-area-end -->

     <!-- tp-team-area-start -->
     <div class="tp-team-area pt-110 pb-120">
        <div class="container-1230 container">
           <div class="row gx-24 align-items-end mb-25"  data-anime='{ "el": "childs", "translateY": [15, 0], "translateX": [-15, 0], "opacity": [0,1], "duration": 500, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
              <div class="col-lg-8">
                 <div class="tp-work-title-wrap mb-25">
                    <span class="tp-ff-poppins fw-600 tp-text-theme-1 d-inline-block mb-15 lh-1">MEET WITH OUR SPEACILIST</span>
                    <h2 class="tp-ff-poppins tp-text-common-black-4 fs-50 fw-600 ls-m-2 lh-110-per">Our qualified doctors</h2>
                 </div>
              </div>
              <div class="col-lg-4">
                 <div class="tp-work-med-btn-wrap text-end mb-30">
                    <a class="tp-work-med-btn fs-16 fw-500  ls-m-2 lh-26 tp-text-theme-1" href="team.html">View all Doctors</a>
                 </div>
              </div>
           </div>
           <div class="row gx-24"  data-anime='{ "el": "childs", "translateY": [15, 0], "translateX": [-15, 0], "opacity": [0,1], "duration": 500, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                 <div class="tp-team-digi-item tp-team-med-item tp-img-hover text-center mb-30">
                    <div class="fix tp-round-6 p-relative mb-15">
                       <img class="w-100 tp-round-6 tp-img-hover-inner" src="../assets-portal/img/medical/team/afiez.jpg" alt="team">
                       <span class="tp-team-med-overly"></span>
                       <div class="tp-team-digi-sicial d-flex tp-bg-common-white">
                          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                          <a href="#"><i class="fa-brands fa-vimeo-v"></i></a>
                          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                          <a href="#">
                             <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25954 0.646484H0.588257L6.12678 7.92395L0.942558 14.0288H3.33782L7.25882 9.41152L10.7405 13.9864H15.4118L9.71235 6.49747L9.72244 6.51039L14.6297 0.731499H12.2345L8.59022 5.02298L5.25954 0.646484ZM3.16672 1.921H4.62096L12.8333 12.7118H11.3791L3.16672 1.921Z" fill="currentColor"/>
                             </svg>
                          </a>
                       </div>
                    </div>
                    <div class="tp-team-digi-content">
                       <h6 class="tp-ff-poppins fw-500 fs-22 ls-m-2 tp-text-common-black-4 mb-0"><a class="underline-black" href="team-details.html">Dr. Afiez</a></h6>
                       <span class="tp-ff-roboto tp-text-grey-9">Neurologist</span>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                 <div class="tp-team-digi-item tp-team-med-item tp-img-hover text-center mb-30">
                    <div class="fix tp-round-6 p-relative mb-15">
                       <img class="w-100 tp-round-6 tp-img-hover-inner" src="../assets-portal/img/medical/team/afiez.jpg" alt="team">
                       <span class="tp-team-med-overly"></span>
                       <div class="tp-team-digi-sicial d-flex tp-bg-common-white">
                          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                          <a href="#"><i class="fa-brands fa-vimeo-v"></i></a>
                          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                          <a href="#">
                             <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25954 0.646484H0.588257L6.12678 7.92395L0.942558 14.0288H3.33782L7.25882 9.41152L10.7405 13.9864H15.4118L9.71235 6.49747L9.72244 6.51039L14.6297 0.731499H12.2345L8.59022 5.02298L5.25954 0.646484ZM3.16672 1.921H4.62096L12.8333 12.7118H11.3791L3.16672 1.921Z" fill="currentColor"/>
                             </svg>
                          </a>
                       </div>
                    </div>
                    <div class="tp-team-digi-content">
                       <h6 class="tp-ff-poppins fw-500 fs-22 ls-m-2 tp-text-common-black-4 mb-0"><a class="underline-black" href="team-details.html">Dr. Raup</a></h6>
                       <span class="tp-ff-roboto tp-text-grey-9">Cardiologists</span>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                 <div class="tp-team-digi-item tp-img-hover tp-team-med-item text-center mb-30">
                    <div class="fix tp-round-6 p-relative mb-15">
                       <img class="w-100 tp-round-6 tp-img-hover-inner" src="../assets-portal/img/medical/team/afiez.jpg" alt="team">
                       <span class="tp-team-med-overly"></span>
                       <div class="tp-team-digi-sicial d-flex tp-bg-common-white">
                          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                          <a href="#"><i class="fa-brands fa-vimeo-v"></i></a>
                          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                          <a href="#">
                             <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25954 0.646484H0.588257L6.12678 7.92395L0.942558 14.0288H3.33782L7.25882 9.41152L10.7405 13.9864H15.4118L9.71235 6.49747L9.72244 6.51039L14.6297 0.731499H12.2345L8.59022 5.02298L5.25954 0.646484ZM3.16672 1.921H4.62096L12.8333 12.7118H11.3791L3.16672 1.921Z" fill="currentColor"/>
                             </svg>
                          </a>
                       </div>
                    </div>
                    <div class="tp-team-digi-content">
                       <h6 class="tp-ff-poppins fw-500 fs-22 ls-m-2 tp-text-common-black-4 mb-0"><a class="underline-black" href="team-details.html">Dr. Nurul Huda</a></h6>
                       <span class="tp-ff-roboto tp-text-grey-9">Endocrinologists</span>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                 <div class="tp-team-digi-item tp-team-med-item tp-img-hover text-center mb-30">
                    <div class="fix tp-round-6 p-relative mb-15">
                       <img class="w-100 tp-round-6 tp-img-hover-inner" src="../assets-portal/img/medical/team/afiez.jpg" alt="team">
                       <span class="tp-team-med-overly"></span>
                       <div class="tp-team-digi-sicial d-flex tp-bg-common-white">
                          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                          <a href="#"><i class="fa-brands fa-vimeo-v"></i></a>
                          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                          <a href="#">
                             <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25954 0.646484H0.588257L6.12678 7.92395L0.942558 14.0288H3.33782L7.25882 9.41152L10.7405 13.9864H15.4118L9.71235 6.49747L9.72244 6.51039L14.6297 0.731499H12.2345L8.59022 5.02298L5.25954 0.646484ZM3.16672 1.921H4.62096L12.8333 12.7118H11.3791L3.16672 1.921Z" fill="currentColor"/>
                             </svg>
                          </a>
                       </div>
                    </div>
                    <div class="tp-team-digi-content">
                       <h6 class="tp-ff-poppins fw-500 fs-22 ls-m-2 tp-text-common-black-4 mb-0"><a class="underline-black" href="team-details.html">Dr. Khadijah</a></h6>
                       <span class="tp-ff-roboto tp-text-grey-9">Dermatologists</span>
                    </div>
                 </div>
              </div>
              <div class="col-12">
                 <div class="text-center pt-25">
                    <p class="tp-ff-poppins fw-500 fs-16 ls-m-2 tp-text-grey-9"><svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <g clip-path="url(#clip0_1012_293)">
                         <path d="M23.8624 0.48291H6.07166C3.11109 0.48291 0.71109 2.88297 0.71109 5.84348V17.5121C0.71109 20.4727 3.11115 22.8726 6.07166 22.8726H8.62488C8.74634 22.8726 8.84478 22.9711 8.84478 23.0925V28.8484C8.84478 29.5289 9.67722 29.8589 10.1434 29.3631L16.1825 22.9418C16.2241 22.8975 16.282 22.8725 16.3427 22.8725H23.8624C26.823 22.8725 29.2229 20.4725 29.2229 17.512V5.84353C29.2231 2.88291 26.823 0.48291 23.8624 0.48291Z" fill="#FF2F4A" />
                         <path d="M11.1001 20.8721C11.1001 20.7441 11.0048 20.6405 10.8872 20.6405H8.4154C5.54929 20.6405 3.22581 18.1132 3.22581 14.9955V2.70799C3.22581 2.18047 3.29255 1.66994 3.41701 1.18555C1.80064 2.10875 0.711029 3.84887 0.711029 5.84357V17.5122C0.711029 20.4727 3.11109 22.8727 6.0716 22.8727H8.62482C8.74628 22.8727 8.84472 22.9712 8.84472 23.0926V28.8485C8.84472 29.529 9.67716 29.859 10.1433 29.3632L11.0999 28.346V20.8721H11.1001Z" fill="#E63950" />
                         <path d="M20.9903 7.43971C19.6293 6.07869 17.4227 6.07869 16.0617 7.43971L15.2929 8.20852C15.1096 8.3918 14.8123 8.3918 14.629 8.20852L13.8725 7.45201C12.5115 6.091 10.3048 6.091 8.94381 7.45201C7.5828 8.81303 7.5828 11.0197 8.94381 12.3807L14.6295 18.0663C14.8127 18.2496 15.11 18.2496 15.2933 18.0663L20.9903 12.3684C22.3513 11.0074 22.3513 8.80072 20.9903 7.43971Z" fill="#EC5569" />
                         <path d="M20.9903 7.43971C19.6293 6.07869 17.4227 6.07869 16.0617 7.43971L15.2929 8.20852C15.1096 8.3918 14.8123 8.3918 14.629 8.20852L13.8725 7.45201C12.5115 6.091 10.3048 6.091 8.94381 7.45201C7.5828 8.81303 7.5828 11.0197 8.94381 12.3807L14.6295 18.0663C14.8127 18.2496 15.11 18.2496 15.2933 18.0663L20.9903 12.3684C22.3513 11.0074 22.3513 8.80072 20.9903 7.43971Z" fill="#FFF5F5" />
                         <path d="M10.7669 12.3807C9.40588 11.0197 9.40588 8.81308 10.7669 7.45207C11.2133 7.00564 11.751 6.70658 12.3197 6.55306C11.1546 6.23847 9.8584 6.53748 8.94387 7.45207C7.58286 8.81308 7.58286 11.0197 8.94387 12.3807L14.6295 18.0664C14.8128 18.2497 15.11 18.2497 15.2934 18.0664L15.8729 17.4868L10.7669 12.3807Z" fill="#C9E1ED" />
                         <path d="M23.8954 0H6.1046C4.85192 0 3.65913 0.392988 2.65518 1.13637C2.45942 1.28133 2.41829 1.55754 2.56319 1.7533C2.70815 1.94906 2.98436 1.99014 3.18012 1.84529C4.03102 1.21518 5.04229 0.88207 6.1046 0.88207H23.8954C26.608 0.88207 28.8149 3.089 28.8149 5.8016V17.4702C28.8149 20.1829 26.608 22.3897 23.8954 22.3897H16.3758C16.1941 22.3897 16.0185 22.4657 15.8943 22.5979L9.85518 29.0192C9.72481 29.1577 9.57364 29.1184 9.51475 29.0951C9.45581 29.0718 9.31876 28.9969 9.31876 28.8066V23.0508C9.31876 22.6863 9.02227 22.3898 8.65782 22.3898H6.1046C3.39194 22.3898 1.18501 20.1829 1.18501 17.4703V5.8016C1.18501 4.80363 1.48202 3.84287 2.04399 3.02326C2.18169 2.82234 2.13053 2.54789 1.92967 2.41014C1.72887 2.27238 1.45424 2.32365 1.31655 2.52445C0.653502 3.4916 0.302994 4.6248 0.302994 5.8016V17.4702C0.302994 20.6692 2.90561 23.2718 6.10465 23.2718H8.43674V28.8066C8.43674 29.2987 8.73229 29.7338 9.18973 29.9151C9.33393 29.9722 9.4837 30 9.6317 30C9.95333 30 10.2669 29.8689 10.4977 29.6235L16.4714 23.2719H23.8954C27.0944 23.2719 29.697 20.6692 29.697 17.4703V5.8016C29.697 2.60262 27.0945 0 23.8954 0Z" fill="#2B265D" />
                         <path d="M19.3946 13.3318C19.2223 13.504 19.2223 13.7833 19.3946 13.9554C19.5669 14.1276 19.8462 14.1277 20.0183 13.9554L21.3351 12.6384C22.8658 11.1077 22.8658 8.61678 21.335 7.08602C19.8043 5.55525 17.3135 5.55537 15.7826 7.08602L15.0139 7.85477C15.0119 7.85682 15.0055 7.86314 14.9938 7.86314C14.9821 7.86314 14.9758 7.85693 14.9737 7.85477L14.2172 7.09832C12.6865 5.56756 10.1957 5.56762 8.6649 7.09832C7.13414 8.62908 7.13414 11.12 8.6649 12.6507L14.3505 18.3364C14.5225 18.5083 14.7512 18.603 14.9943 18.603C15.2375 18.603 15.4662 18.5083 15.6381 18.3363L18.7626 15.2113C18.9349 15.039 18.9349 14.7597 18.7625 14.5876C18.5903 14.4154 18.311 14.4153 18.1389 14.5877L15.0144 17.7127C15.0123 17.7147 15.006 17.721 14.9943 17.721C14.9825 17.721 14.9763 17.7148 14.9742 17.7126L9.28857 12.027C8.1017 10.8401 8.1017 8.90893 9.28857 7.72199C10.4754 6.53506 12.4066 6.53518 13.5935 7.72199L14.35 8.47844C14.5219 8.65047 14.7506 8.74516 14.9937 8.74516C15.237 8.74516 15.4655 8.65047 15.6376 8.4785L16.4063 7.70975C17.5934 6.52293 19.5244 6.52299 20.7113 7.70975C21.8982 8.89662 21.8982 10.8278 20.7113 12.0147L19.3946 13.3318Z" fill="#2B265D" />
                       </g>
                       <defs>
                         <clipPath id="clip0_1012_293">
                           <rect width="30" height="30" fill="white" />
                         </clipPath>
                       </defs>
                     </svg>
                     Every life are more important. They work from their heart.</p>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-team-area-end -->


@endsection
