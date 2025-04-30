@extends('layouts.portal')
@section('title', 'Laman Utama')

@section('content')


      <!-- breadcrumb-area-start -->
      <div class="tp-breadcrumb-area tp-breadcrumb-med-spacing tp-breadcrumb-med-bg p-relative">
        <img class="tp-breadcrumb-med-shape p-absolute" data-parallax='{"y": 100, "smoothness": 20}' src="../assets/img/medical/bradcrumb/shape.png" alt="shape">
        <div class="container container-1230">
           <div class="row gx-24">
              <div class="col-12">
                 <div  data-anime='{"el": "childs", "translateY": [15, 0], "perspective": [1200,1200], "scale": [1.05, 1], "rotateX": [30, 0], "opacity": [0,1], "duration": 800, "delay": 100, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <h2 class="fw-600 fs-50 fs-sm-40 ls-m-4 tp-ff-poppins tp-text-common-black-4">About hospital</h2>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- breadcrumb-area-end -->

     <!-- tp-about-area-start -->
     <div class="tp-about-area pb-120 p-relative">
        <div class="container-1430 container">
           <div class="tp-about-med-2-inner tp-bg-common-white">
              <div class="row gx-24">
                 <div class="col-lg-6">
                    <div class="tp-about-med-2-thumb p-relative h-100">
                       <img class="w-100 thumb" src="../assets/img/medical/about/about.jpg" alt="thumb">
                       <div class="tp-about-med-2-shape p-absolute d-flex align-items-center tp-bg-common-white tp-round-10 updown">
                          <span class="tp-bg-grey-10 text-center d-inline-block rounded-circle mr-10">
                             <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1020_654)">
                                  <path d="M15.3333 7.33374H14V6.00041C14 5.82359 13.9297 5.65403 13.8047 5.529C13.6797 5.40398 13.5101 5.33374 13.3333 5.33374C13.1565 5.33374 12.9869 5.40398 12.8619 5.529C12.7369 5.65403 12.6666 5.82359 12.6666 6.00041V7.33374H11.3333C11.1565 7.33374 10.9869 7.40397 10.8619 7.529C10.7369 7.65402 10.6666 7.82359 10.6666 8.0004C10.6666 8.17721 10.7369 8.34678 10.8619 8.47181C10.9869 8.59683 11.1565 8.66707 11.3333 8.66707H12.6666V10.0004C12.6666 10.1772 12.7369 10.3468 12.8619 10.4718C12.9869 10.5968 13.1565 10.6671 13.3333 10.6671C13.5101 10.6671 13.6797 10.5968 13.8047 10.4718C13.9297 10.3468 14 10.1772 14 10.0004V8.66707H15.3333C15.5101 8.66707 15.6797 8.59683 15.8047 8.47181C15.9297 8.34678 15.9999 8.17721 15.9999 8.0004C15.9999 7.82359 15.9297 7.65402 15.8047 7.529C15.6797 7.40397 15.5101 7.33374 15.3333 7.33374Z" fill="#6C60DC" />
                                  <path d="M6 8C6.79113 8 7.56449 7.76541 8.22228 7.32588C8.88008 6.88635 9.39277 6.26164 9.69552 5.53074C9.99827 4.79983 10.0775 3.99556 9.92314 3.21964C9.7688 2.44372 9.38784 1.73098 8.82843 1.17157C8.26902 0.612165 7.55629 0.231202 6.78036 0.0768607C6.00444 -0.0774802 5.20017 0.00173314 4.46927 0.304484C3.73836 0.607234 3.11365 1.11992 2.67412 1.77772C2.2346 2.43552 2 3.20888 2 4C2.00106 5.06054 2.42283 6.07734 3.17274 6.82726C3.92266 7.57718 4.93946 7.99894 6 8ZM6 1.33334C6.52742 1.33334 7.04299 1.48973 7.48152 1.78275C7.92005 2.07577 8.26185 2.49224 8.46368 2.97951C8.66551 3.46678 8.71832 4.00296 8.61543 4.52024C8.51253 5.03753 8.25856 5.51268 7.88562 5.88562C7.51268 6.25856 7.03752 6.51254 6.52024 6.61543C6.00296 6.71832 5.46678 6.66551 4.97951 6.46368C4.49224 6.26185 4.07576 5.92005 3.78275 5.48152C3.48973 5.04299 3.33333 4.52742 3.33333 4C3.33333 3.29276 3.61428 2.61448 4.11438 2.11438C4.61448 1.61429 5.29276 1.33334 6 1.33334V1.33334Z" fill="#6C60DC" />
                                  <path d="M6 9.33374C4.40924 9.3355 2.88414 9.96821 1.75931 11.093C0.634472 12.2179 0.00176457 13.743 0 15.3337C0 15.5106 0.0702379 15.6801 0.195262 15.8051C0.320286 15.9302 0.489856 16.0004 0.666667 16.0004C0.843478 16.0004 1.01305 15.9302 1.13807 15.8051C1.2631 15.6801 1.33333 15.5106 1.33333 15.3337C1.33333 14.0961 1.825 12.9091 2.70017 12.0339C3.57534 11.1587 4.76232 10.6671 6 10.6671C7.23768 10.6671 8.42466 11.1587 9.29983 12.0339C10.175 12.9091 10.6667 14.0961 10.6667 15.3337C10.6667 15.5106 10.7369 15.6801 10.8619 15.8051C10.987 15.9302 11.1565 16.0004 11.3333 16.0004C11.5101 16.0004 11.6797 15.9302 11.8047 15.8051C11.9298 15.6801 12 15.5106 12 15.3337C11.9982 13.743 11.3655 12.2179 10.2407 11.093C9.11586 9.96821 7.59076 9.3355 6 9.33374V9.33374Z" fill="#6C60DC" />
                                </g>
                                <defs>
                                  <clipPath id="clip0_1020_654">
                                    <rect width="16" height="16" fill="white" />
                                  </clipPath>
                                </defs>
                             </svg>
                          </span>
                          <h3 class="tp-ff-poppins fw-500 fs-16 ls-m-2 tp-text-common-black-4 mb-0 lh-18">More than 20k
                             patients treated!</h3>
                       </div>
                    </div>
                 </div>
                 <div class="col-lg-6 d-flex align-items-center">
                    <div class="tp-about-med-2-content" data-anime='{ "el": "childs", "translateX": [20, 0], "opacity": [0,1], "duration": 600, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
                       <span class="tp-ff-poppins fw-600 tp-text-theme-1 d-inline-block mb-15 lh-1">THE NO 1 HOSPITAL</span>
                       <h2 class="tp-ff-poppins tp-text-common-black-4 fs-50 fs-xl-42 fs-xs-35 fw-600 ls-m-2 lh-110-per mb-20">Solutions to improve patient care</h2>
                       <p class="tp-text-grey-9 tp-ff-roboto ls-0 lh-30 mb-30">Med always help to provide proper treatment for most of get the proper cure  and healthy life which is the cooperate  focus thing for us
                          patient our  main propriety services</p>
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
     </div>
     <!-- tp-about-area-end -->

     <!-- tp-counter-area-start -->
     <div class="tp-counter-area pb-80">
        <div class="container-1230 container">
           <div class="row gx-24">
              <div class="col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-counter-med-item counter-style-01 text-center">
                    <div class="d-inline-block p-relative">
                       <span class="vertical-counter d-inline-flex mb-5 p-relative z-index-1 tp-text-common-black-4 lh-1 fw-600 tp-ff-poppins fs-80 fs-sm-50 ls-m-4" data-text="+" data-to="594"></span>
                       <div class="tp-funfact-digi-border"></div>
                    </div>
                    <p class="tp-text-grey-9 tp-ff-poppins ls-m-1 fs-18 fw-500 mb-55">Worldwide branches</p>
                 </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-counter-med-item counter-style-01 text-center">
                    <div class="d-inline-block p-relative">
                       <span class="vertical-counter lh-1 d-inline-flex mb-5 p-relative z-index-1 tp-text-common-black-4 fw-600 tp-ff-poppins fs-80 fs-sm-50 ls-m-4" data-text="%" data-to="98"></span>
                       <div class="tp-funfact-digi-border"></div>
                    </div>
                    <p class="tp-text-grey-9 tp-ff-poppins ls-m-1 fs-18 fw-500 mb-55">Genuine clients feedback</p>
                 </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-counter-med-item counter-style-01 text-center">
                    <div class="d-inline-block p-relative">
                       <span class="vertical-counter lh-1 d-inline-flex mb-5 p-relative z-index-1 tp-text-common-black-4 fw-600 tp-ff-poppins fs-80 fs-sm-50 ls-m-4" data-text="+" data-to="374"></span>
                       <div class="tp-funfact-digi-border"></div>
                    </div>
                    <p class="tp-text-grey-9 tp-ff-poppins ls-m-1 fs-18 fw-500 mb-55">Daily patient consulted</p>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-counter-area-end -->      

     <!-- tp-feature-area-start -->
     <div class="tp-feature-area tp-feature-med-bg pt-140 pb-120">
        <div class="container-1230 container">
           <div class="row gx-24 align-items-center" data-anime='{ "el": "childs", "scale": [0, 1], "opacity": [0,1], "duration": 600, "delay": 100, "staggervalue": 100, "easing": "easeOutQuad" }'>
              <div class="col-xl-3 col-lg-12 col-md-6 col-sm-6">
                 <div class="tp-feature-med-title-wrap mb-20">
                    <h2 class="tp-ff-poppins fw-600 fs-36 ls-m-2 tp-text-common-black-4 lh-110-per mb-25">Clinical
                       excellence</h2>
                       <a href="tel:1234564890" class="tp-ff-poppins d-flex align-items-center fw-500 fs-16 tp-text-common-black-4">
                          <span class="mr-5 tp-transfrom">
                             <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.4775 13.7474C16.4775 14.0174 16.4175 14.2949 16.29 14.5649C16.1625 14.8349 15.9975 15.0899 15.78 15.3299C15.4125 15.7349 15.0075 16.0274 14.55 16.2149C14.1 16.4024 13.6125 16.4999 13.0875 16.4999C12.3225 16.4999 11.505 16.3199 10.6425 15.9524C9.78 15.5849 8.9175 15.0899 8.0625 14.4674C7.2 13.8374 6.3825 13.1399 5.6025 12.3674C4.83 11.5874 4.1325 10.7699 3.51 9.91488C2.895 9.05988 2.4 8.20488 2.04 7.35738C1.68 6.50238 1.5 5.68488 1.5 4.90488C1.5 4.39488 1.59 3.90738 1.77 3.45738C1.95 2.99988 2.235 2.57988 2.6325 2.20488C3.1125 1.73238 3.6375 1.49988 4.1925 1.49988C4.4025 1.49988 4.6125 1.54488 4.8 1.63488C4.995 1.72488 5.1675 1.85988 5.3025 2.05488L7.0425 4.50738C7.1775 4.69488 7.275 4.86738 7.3425 5.03238C7.41 5.18988 7.4475 5.34738 7.4475 5.48988C7.4475 5.66988 7.395 5.84988 7.29 6.02238C7.1925 6.19488 7.05 6.37488 6.87 6.55488L6.3 7.14738C6.2175 7.22988 6.18 7.32738 6.18 7.44738C6.18 7.50738 6.1875 7.55988 6.2025 7.61988C6.225 7.67988 6.2475 7.72488 6.2625 7.76988C6.3975 8.01738 6.63 8.33988 6.96 8.72988C7.2975 9.11988 7.6575 9.51738 8.0475 9.91488C8.4525 10.3124 8.8425 10.6799 9.24 11.0174C9.63 11.3474 9.9525 11.5724 10.2075 11.7074C10.245 11.7224 10.29 11.7449 10.3425 11.7674C10.4025 11.7899 10.4625 11.7974 10.53 11.7974C10.6575 11.7974 10.755 11.7524 10.8375 11.6699L11.4075 11.1074C11.595 10.9199 11.775 10.7774 11.9475 10.6874C12.12 10.5824 12.2925 10.5299 12.48 10.5299C12.6225 10.5299 12.7725 10.5599 12.9375 10.6274C13.1025 10.6949 13.275 10.7924 13.4625 10.9199L15.945 12.6824C16.14 12.8174 16.275 12.9749 16.3575 13.1624C16.4325 13.3499 16.4775 13.5374 16.4775 13.7474Z" stroke="#8885A6" stroke-width="1.5" stroke-miterlimit="10" />
                                <path d="M12.15 5.85012H15.75M12.15 5.85012V2.25012V5.85012Z" stroke="#8885A6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                             </svg>
                          </span>
                          123 456 4890
                          <span class="badge tp-ff-roboto tp-transfrom ml-5 fs-13 tp-text-common-white tp-bg-theme-1 d-inline-block lh-1 tp-round-4">Free</span>
                       </a>
                 </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-feature-med-content tp-feature-med-content-2 tp-round-10 text-center mb-20">
                    <span class="icon d-inline-block mb-20 rounded-circle tp-text-theme-1 tp-bg-common-white text-center">
                       <svg width="30" height="36" viewBox="0 0 30 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M24.8711 3.21031L15.4738 0.07686C15.1661 -0.02562 14.8335 -0.02562 14.5258 0.07686L5.12842 3.21031C3.6343 3.70663 2.33459 4.66118 1.41395 5.93834C0.493303 7.2155 -0.00144249 8.75031 3.15913e-06 10.3247V18.0001C3.15913e-06 29.3444 13.7998 35.6098 14.3908 35.8708C14.5825 35.956 14.79 36 14.9998 36C15.2096 36 15.417 35.956 15.6088 35.8708C16.1998 35.6098 29.9995 29.3444 29.9995 18.0001V10.3247C30.001 8.75031 29.5062 7.2155 28.5856 5.93834C27.665 4.66118 26.3652 3.70663 24.8711 3.21031ZM26.9996 18.0001C26.9996 26.1825 17.5212 31.5494 14.9998 32.8334C12.4753 31.5539 2.99996 26.2035 2.99996 18.0001V10.3247C3.00004 9.38013 3.29736 8.45954 3.84979 7.69336C4.40222 6.92717 5.18174 6.35425 6.07791 6.05577L14.9998 3.08131L23.9216 6.05577C24.8178 6.35425 25.5973 6.92717 26.1498 7.69336C26.7022 8.45954 26.9995 9.38013 26.9996 10.3247V18.0001Z" fill="currentColor" />
                          <path d="M19.9494 12.4495L13.6675 18.7494L10.3015 15.2395C10.1664 15.0918 10.0032 14.9726 9.82132 14.889C9.63948 14.8054 9.44274 14.759 9.24269 14.7526C9.04264 14.7462 8.84333 14.7799 8.65651 14.8517C8.46968 14.9235 8.29912 15.032 8.15487 15.1707C8.01061 15.3095 7.89559 15.4757 7.81656 15.6596C7.73754 15.8435 7.69612 16.0413 7.69474 16.2415C7.69336 16.4416 7.73205 16.64 7.80853 16.825C7.88501 17.0099 7.99773 17.1777 8.14006 17.3184L11.599 20.9184C11.857 21.197 12.1688 21.4204 12.5155 21.5751C12.8623 21.7298 13.2368 21.8126 13.6165 21.8184H13.666C14.0381 21.8196 14.4067 21.7469 14.7504 21.6045C15.0942 21.4621 15.4062 21.2529 15.6684 20.9889L22.0763 14.581C22.2163 14.4413 22.3274 14.2755 22.4032 14.0929C22.4791 13.9103 22.5182 13.7145 22.5184 13.5168C22.5187 13.3191 22.4799 13.1233 22.4045 12.9405C22.329 12.7578 22.2183 12.5917 22.0786 12.4517C21.9389 12.3118 21.7731 12.2007 21.5905 12.1249C21.4079 12.049 21.2122 12.0099 21.0145 12.0096C20.8167 12.0094 20.6209 12.0482 20.4382 12.1236C20.2554 12.1991 20.0893 12.3098 19.9494 12.4495Z" fill="currentColor" />
                       </svg>
                    </span>
                    <div>
                       <h3 class="tp-ff-poppins fw-500 fs-24 ls-m-2 tp-text-common-black-4 mb-10">Safety first</h3>
                       <span class="tp-ff-roboto fs-16 tp-text-grey-9 ls-m-0">Lorem ipsum dolor sit amet. Ut inventore consequatur</span>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-feature-med-content tp-feature-med-content-2 tp-round-10 text-center mb-20">
                    <span class="icon d-inline-block mb-20 rounded-circle tp-text-theme-1 tp-bg-common-white text-center">
                       <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M36 13.5C36.0034 12.6742 35.7795 11.8634 35.3529 11.1564C34.9262 10.4494 34.3133 9.87338 33.5811 9.49149C32.8489 9.1096 32.0258 8.93654 31.2018 8.99127C30.3778 9.04599 29.5848 9.32639 28.9096 9.80176C28.2344 10.2771 27.7029 10.9291 27.3735 11.6864C27.0441 12.4436 26.9295 13.2769 27.0421 14.095C27.1547 14.913 27.4902 15.6844 28.0119 16.3244C28.5336 16.9645 29.2214 17.4487 30 17.724V24C30 26.3869 29.0518 28.6761 27.364 30.364C25.6761 32.0518 23.3869 33 21 33C18.6131 33 16.3239 32.0518 14.636 30.364C12.9482 28.6761 12 26.3869 12 24V23.88C14.4961 23.5178 16.7787 22.27 18.431 20.3643C20.0834 18.4586 20.9952 16.0223 21 13.5V7.5C20.9976 5.51161 20.2067 3.60534 18.8007 2.19933C17.3947 0.793323 15.4884 0.00238179 13.5 0C13.1022 0 12.7206 0.158035 12.4393 0.43934C12.158 0.720644 12 1.10218 12 1.5C12 1.89782 12.158 2.27936 12.4393 2.56066C12.7206 2.84196 13.1022 3 13.5 3C14.6935 3 15.8381 3.47411 16.682 4.31802C17.5259 5.16193 18 6.30653 18 7.5V13.5C18 15.4891 17.2098 17.3968 15.8033 18.8033C14.3968 20.2098 12.4891 21 10.5 21C8.51088 21 6.60322 20.2098 5.1967 18.8033C3.79018 17.3968 3 15.4891 3 13.5V7.5C3 6.30653 3.47411 5.16193 4.31802 4.31802C5.16193 3.47411 6.30653 3 7.5 3C7.89782 3 8.27936 2.84196 8.56066 2.56066C8.84196 2.27936 9 1.89782 9 1.5C9 1.10218 8.84196 0.720644 8.56066 0.43934C8.27936 0.158035 7.89782 0 7.5 0C5.51161 0.00238179 3.60534 0.793323 2.19933 2.19933C0.793323 3.60534 0.00238179 5.51161 0 7.5L0 13.5C0.00482393 16.0223 0.916637 18.4586 2.56896 20.3643C4.22128 22.27 6.50388 23.5178 9 23.88V24C9 27.1826 10.2643 30.2348 12.5147 32.4853C14.7652 34.7357 17.8174 36 21 36C24.1826 36 27.2348 34.7357 29.4853 32.4853C31.7357 30.2348 33 27.1826 33 24V17.724C33.8744 17.4149 34.6319 16.8432 35.1689 16.087C35.7059 15.3309 35.9962 14.4274 36 13.5ZM31.5 15C31.2033 15 30.9133 14.912 30.6666 14.7472C30.42 14.5824 30.2277 14.3481 30.1142 14.074C30.0006 13.7999 29.9709 13.4983 30.0288 13.2074C30.0867 12.9164 30.2296 12.6491 30.4393 12.4393C30.6491 12.2296 30.9164 12.0867 31.2074 12.0288C31.4983 11.9709 31.7999 12.0006 32.074 12.1142C32.3481 12.2277 32.5824 12.42 32.7472 12.6666C32.912 12.9133 33 13.2033 33 13.5C33 13.8978 32.842 14.2794 32.5607 14.5607C32.2794 14.842 31.8978 15 31.5 15Z" fill="currentColor" />
                       </svg>
                    </span>
                    <div>
                       <h3 class="tp-ff-poppins fw-500 fs-24 ls-m-2 tp-text-common-black-4 mb-10">Quality doctor</h3>
                       <span class="tp-ff-roboto fs-16 tp-text-grey-9 ls-m-0">Lorem ipsum dolor sit amet. Ut inventore consequatur</span>
                    </div>
                 </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                 <div class="tp-feature-med-content tp-feature-med-content-2 tp-round-10 text-center mb-20">
                    <span class="icon d-inline-block mb-20 rounded-circle tp-text-theme-1 tp-bg-common-white text-center">
                       <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M34.4992 25.4946C34.1012 25.4946 33.7195 25.6527 33.438 25.9342C33.1566 26.2156 32.9985 26.5974 32.9985 26.9954V30.8764L28.5052 26.3831C31.7978 22.8683 34.4992 18.8117 34.4992 14.9697C34.5821 12.6929 33.7605 10.4759 32.2139 8.80295C30.6672 7.13005 28.5213 6.13731 26.245 6.04166C24.8912 6.05929 23.5636 6.41642 22.3836 7.08032C21.2037 7.74422 20.2093 8.69366 19.4915 9.84161C18.7738 8.69366 17.7794 7.74422 16.5995 7.08032C15.4195 6.41642 14.0918 6.05929 12.7381 6.04166C11.4654 6.04537 10.2134 6.36455 9.09422 6.97064L2.54337 0.421283C2.26032 0.147906 1.88122 -0.00336265 1.48773 5.6733e-05C1.09423 0.00347611 0.71782 0.16131 0.439565 0.439565C0.16131 0.71782 0.00347611 1.09423 5.6733e-05 1.48773C-0.00336265 1.88122 0.147906 2.26032 0.421283 2.54337L6.73502 8.8586C5.27155 10.5571 4.47197 12.7277 4.48386 14.9697C4.48386 20.6591 10.4134 26.8258 15.3869 30.9919C16.5361 31.9589 17.9897 32.4891 19.4915 32.4891C20.9934 32.4891 22.447 31.9589 23.5961 30.9919C24.5086 30.2265 25.4541 29.3936 26.3891 28.5097L30.8764 32.9985H26.9954C26.5974 32.9985 26.2156 33.1566 25.9342 33.438C25.6527 33.7195 25.4946 34.1012 25.4946 34.4992C25.4946 34.8973 25.6527 35.279 25.9342 35.5604C26.2156 35.8419 26.5974 36 26.9954 36H32.9985C33.7945 36 34.558 35.6838 35.1209 35.1209C35.6838 34.558 36 33.7945 36 32.9985V26.9954C36 26.5974 35.8419 26.2156 35.5604 25.9342C35.279 25.6527 34.8973 25.4946 34.4992 25.4946ZM21.6677 28.6943C21.0512 29.191 20.2833 29.4619 19.4915 29.4619C18.6998 29.4619 17.9319 29.191 17.3154 28.6943C11.0677 23.4581 7.4854 18.456 7.4854 14.9697C7.40316 13.489 7.90886 12.0357 8.89251 10.9259C9.87616 9.81602 11.2582 9.13941 12.7381 9.0432C14.218 9.13941 15.6 9.81602 16.5837 10.9259C17.5673 12.0357 18.073 13.489 17.9908 14.9697C17.9908 15.3678 18.1489 15.7495 18.4303 16.0309C18.7118 16.3124 19.0935 16.4705 19.4915 16.4705C19.8896 16.4705 20.2713 16.3124 20.5528 16.0309C20.8342 15.7495 20.9923 15.3678 20.9923 14.9697C20.9101 13.489 21.4158 12.0357 22.3994 10.9259C23.3831 9.81602 24.7651 9.13941 26.245 9.0432C27.7249 9.13941 29.1069 9.81602 30.0906 10.9259C31.0742 12.0357 31.5799 13.489 31.4977 14.9697C31.4977 18.4545 27.9139 23.4566 21.6677 28.6943Z" fill="currentColor" />
                       </svg>
                    </span>
                    <div>
                       <h3 class="tp-ff-poppins fw-500 fs-24 ls-m-2 tp-text-common-black-4 mb-10">Satisfaction</h3>
                       <span class="tp-ff-roboto fs-16 tp-text-grey-9 ls-m-0">Lorem ipsum dolor sit amet. Ut inventore consequatur</span>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-feature-area-end -->

     <!-- tp-banner-med-area-start -->
     <div class="tp-banner-area pb-140">
        <div class="container-1230 container">
           <div class="tp-banner-med-bg bg-position tp-round-10 fix" data-background="../assets/img/medical/vison/bg.jpg">
              <div class="row gx-24 align-items-end">
                 <div class="col-lg-7">
                    <div class="tp-banner-med-left tp-round-10 ml-80 mr-70 mt-120 mb-120" data-parallax='{"y": -50, "smoothness": 20}'>
                       <div class="row align-items-center">
                          <div class="col-lg-5 col-md-5">
                             <div class="tp-banner-med-experience">
                                <h2 class="tp-ff-jakarta fw-700 ls-m-4">9</h2>
                                <span class="tp-ff-poppins fw-500 fs-18 ls-m-1">Years experience</span>
                             </div>
                          </div>
                          <div class="col-lg-7 col-md-7">
                             <div class="tp-banner-med-para">
                                <h4 class="tp-ff-poppins fw-500 fs-24 ls-m-2 tp-text-common-white mb-20">Our vision</h4>
                                <p class="fs-17 tp-ff-poppins mb-30">Chesterton, a writer of paradox
                                   on our  list. He speaks for the
                                   must buck conven</p>
                                <a href="#" class="tp-ff-poppins fw-500 ls-m-2 tp-text-common-white hover-text-white">Find Your Doctor</a>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="col-lg-5">
                    <div class="tp-banner-med-right h-100 mr-30">
                       <img class="w-100" data-parallax='{"y": 100, "smoothness": 20}' src="../assets/img/medical/vison/thumb.png" alt="">
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-banner-med-area-end -->

     <!-- tp-about-area-start -->
     <div class="tp-about-area pb-120">
        <div class="container container-1230">
           <div class="row gx-24">
              <div class="col-xxl-7 col-xxl-6 col-lg-6">
                 <div class="tp-about-med-3-left mb-30">
                    <div class="tp-about-med-3-thumb">
                       <img class="tp-round-6 w-100" data-parallax='{"y": 20, "smoothness": 20}' src="../assets/img/medical/about/details/about.jpg" alt="">
                    </div>
                    <div class="tp-about-med-3-thumb-2">
                       <img class="tp-round-4 w-100" data-parallax='{"y": -50, "smoothness": 20}' src="../assets/img/medical/about/details/about-2.jpg" alt="">
                    </div>
                 </div>
              </div>
              <div class="col-xxl-5 col-xl-6 col-lg-6">
                 <div class="tp-about-lwr-content tp-about-med-3-content mb-30" data-anime='{ "el": "childs", "translateX": [20, 0], "opacity": [0,1], "duration": 600, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <h2 class="tp-ff-poppins tp-text-common-black-4 lh-110-per fs-50 fs-sm-40 fs-lg-45 fw-600 ls-m-2 mb-20">Quality healthcare solution for all</h2>
                    <p class="tp-text-grey-9 tp-ff-roboto ls-0 lh-30 mb-30">Med always help to provide proper treatment for most of get the
                       proper cure  and healthy life which is the cooperate  focus
                       thing for us patient our  main propriety services</p>
                    <div class="tp-about-med-list mb-50">
                       <ul>
                          <li>
                             <div class="d-flex align-items-center mb-15">
                                <span class="icon rounded-circle text-center mr-15">
                                   <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M23.0063 21.0139H21.7273L20.4063 17.0649C20.2074 16.4684 19.8261 15.9495 19.3163 15.5815C18.8065 15.2134 18.194 15.0149 17.5653 15.0139H14.0063C13.4758 15.0139 12.9671 14.8031 12.592 14.4281C12.217 14.053 12.0063 13.5443 12.0063 13.0139H18.0063C18.2715 13.0139 18.5258 12.9085 18.7134 12.721C18.9009 12.5334 19.0063 12.2791 19.0063 12.0139C19.0063 11.7486 18.9009 11.4943 18.7134 11.3067C18.5258 11.1192 18.2715 11.0139 18.0063 11.0139H12.0063V8.01385C12.0002 7.96864 11.9912 7.92387 11.9793 7.87985C12.9259 7.64245 13.7531 7.06713 14.3051 6.26224C14.8571 5.45735 15.0957 4.47842 14.9761 3.50981C14.8565 2.54119 14.3869 1.64972 13.6557 1.00327C12.9245 0.356821 11.9822 0 11.0063 0C10.0303 0 9.08798 0.356821 8.3568 1.00327C7.62561 1.64972 7.15601 2.54119 7.0364 3.50981C6.9168 4.47842 7.15545 5.45735 7.70742 6.26224C8.25939 7.06713 9.08659 7.64245 10.0333 7.87985C10.0213 7.92387 10.0123 7.96864 10.0063 8.01385V13.0139C10.0083 14.0579 10.4186 15.0598 11.1493 15.8055C11.8801 16.5512 12.8734 16.9816 13.9173 17.0049C13.7345 18.0978 13.2526 19.1187 12.5251 19.9545C11.7976 20.7903 10.8528 21.4083 9.79551 21.74C8.73823 22.0717 7.60975 22.1042 6.53516 21.8338C5.46058 21.5633 4.48187 21.0006 3.70753 20.208C2.9332 19.4153 2.39349 18.4237 2.14824 17.3431C1.90299 16.2625 1.96177 15.1351 2.31808 14.0859C2.67439 13.0366 3.3143 12.1065 4.16687 11.3987C5.01944 10.6909 6.05137 10.2331 7.14825 10.0759C7.41095 10.0382 7.64792 9.89772 7.80705 9.68534C7.96617 9.47295 8.03441 9.20605 7.99675 8.94335C7.95909 8.68066 7.81862 8.44368 7.60623 8.28456C7.39385 8.12543 7.12695 8.05719 6.86425 8.09486C5.38822 8.30655 4.00073 8.92656 2.85835 9.88492C1.71597 10.8433 0.864176 12.1019 0.399087 13.5186C-0.066002 14.9354 -0.125873 16.4539 0.226229 17.9029C0.578331 19.3518 1.32839 20.6736 2.39177 21.7189C3.45515 22.7642 4.78952 23.4915 6.2443 23.8188C7.69909 24.146 9.21638 24.0602 10.625 23.5709C12.0335 23.0816 13.2773 22.2084 14.216 21.0498C15.1547 19.8912 15.7509 18.4933 15.9373 17.0139H17.5653C17.7752 17.0138 17.9799 17.0798 18.1502 17.2026C18.3205 17.3254 18.4479 17.4986 18.5143 17.6979L20.0583 22.3299C20.1245 22.5289 20.2518 22.7021 20.4219 22.8248C20.592 22.9476 20.7965 23.0137 21.0063 23.0139H23.0063C23.2715 23.0139 23.5258 22.9085 23.7134 22.721C23.9009 22.5334 24.0063 22.2791 24.0063 22.0139C24.0063 21.7486 23.9009 21.4943 23.7134 21.3067C23.5258 21.1192 23.2715 21.0139 23.0063 21.0139ZM9.00625 4.01385C9.00625 3.61829 9.12355 3.23161 9.34331 2.90271C9.56308 2.57382 9.87543 2.31747 10.2409 2.1661C10.6063 2.01472 11.0085 1.97511 11.3964 2.05228C11.7844 2.12945 12.1408 2.31994 12.4205 2.59964C12.7002 2.87935 12.8907 3.23571 12.9678 3.62367C13.045 4.01164 13.0054 4.41377 12.854 4.77922C12.7026 5.14467 12.4463 5.45703 12.1174 5.67679C11.7885 5.89656 11.4018 6.01385 11.0063 6.01385C10.4758 6.01385 9.96711 5.80314 9.59204 5.42807C9.21697 5.053 9.00625 4.54429 9.00625 4.01385Z" fill="#6C60DC" />
                                   </svg>
                                </span>
                                <h5 class="tp-ff-poppins fw-500 fs-18 ls-m-2 tp-text-common-black-4 lh-22">Saving 14000+ patients<br>
                                   annually</h5>
                             </div>
                          </li>
                          <li>
                             <div class="d-flex align-items-center">
                                <span class="icon rounded-circle text-center mr-15">
                                   <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M17.95 5.53603L14.464 2.05002C13.8156 1.3981 13.0443 0.881248 12.1948 0.529368C11.3453 0.177488 10.4345 -0.00243056 9.515 2.47962e-05H5C3.67441 0.00161265 2.40356 0.528907 1.46622 1.46624C0.528882 2.40358 0.00158786 3.67443 0 5.00003V19C0.00158786 20.3256 0.528882 21.5965 1.46622 22.5338C2.40356 23.4711 3.67441 23.9984 5 24H15C16.3256 23.9984 17.5964 23.4711 18.5338 22.5338C19.4711 21.5965 19.9984 20.3256 20 19V10.485C20.0025 9.56554 19.8225 8.6547 19.4707 7.80521C19.1188 6.95572 18.6019 6.18445 17.95 5.53603ZM16.536 6.95003C16.841 7.26443 17.1036 7.61744 17.317 8.00003H13C12.7348 8.00003 12.4804 7.89467 12.2929 7.70713C12.1054 7.5196 12 7.26524 12 7.00003V2.68303C12.3826 2.89641 12.7356 3.15898 13.05 3.46403L16.536 6.95003ZM18 19C18 19.7957 17.6839 20.5587 17.1213 21.1213C16.5587 21.684 15.7956 22 15 22H5C4.20435 22 3.44129 21.684 2.87868 21.1213C2.31607 20.5587 2 19.7957 2 19V5.00003C2 4.20438 2.31607 3.44131 2.87868 2.8787C3.44129 2.3161 4.20435 2.00003 5 2.00003H9.515C9.68 2.00003 9.838 2.03202 10 2.04702V7.00003C10 7.79568 10.3161 8.55874 10.8787 9.12135C11.4413 9.68396 12.2044 10 13 10H17.953C17.968 10.162 18 10.32 18 10.485V19ZM14.724 13.311C14.9066 13.5031 15.0055 13.7598 14.9989 14.0248C14.9924 14.2897 14.8809 14.5412 14.689 14.724L11.1 18.138C10.5358 18.6933 9.77508 19.0031 8.9835 18.9999C8.19192 18.9967 7.43367 18.6808 6.874 18.121L5.334 16.747C5.13588 16.5704 5.01605 16.3223 5.00086 16.0573C4.99334 15.9261 5.01173 15.7947 5.055 15.6706C5.09826 15.5465 5.16554 15.4321 5.253 15.334C5.34046 15.2359 5.44639 15.156 5.56473 15.0989C5.68307 15.0417 5.81151 15.0084 5.94272 15.0009C6.20771 14.9857 6.46788 15.0764 6.666 15.253L8.251 16.667C8.34174 16.7689 8.45236 16.8512 8.57608 16.9087C8.69979 16.9663 8.83397 16.9979 8.97036 17.0016C9.10675 17.0054 9.24246 16.9812 9.36914 16.9305C9.49583 16.8798 9.61081 16.8038 9.707 16.707L13.307 13.276C13.4022 13.185 13.5144 13.1137 13.6373 13.0662C13.7601 13.0186 13.8911 12.9959 14.0228 12.9991C14.1544 13.0024 14.2842 13.0316 14.4045 13.0851C14.5248 13.1386 14.6334 13.2154 14.724 13.311Z" fill="#6C60DC" />
                                   </svg>
                                </span>
                                <h5 class="tp-ff-poppins fw-500 fs-18 ls-m-2 tp-text-common-black-4 lh-22">Over two decades of<br> trusted service</h5>
                             </div>
                          </li>
                       </ul>
                    </div>
                    <div class="tp-about-med-btn-wrap d-flex flex-wrap">
                       <a href="treatments.html" class="tp-btn-switch-text mb-10 btn-lg bg-hover-black p-relative tp-round-100 d-inline-block hover-text-white tp-bg-theme-1 tp-text-common-white fw-500 tp-ff-poppins lh-26 ls-m-2">
                          <span>
                             <span class="btn-double-text" data-text="View Treatments">View Treatments</span>
                          </span>
                       </a>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-about-area-end -->

     <!-- tp-testimonial-area-start -->
     <div class="tp-testimonial-area pb-85">
        <div class="container">
           <div class="row align-items-end">
              <div class="col-lg-12">
                 <div class="tp-testimonial-title-wrap text-center mb-65" data-anime='{ "el": "childs", "translateX": [20, 0], "opacity": [0,1], "duration": 600, "delay": 300, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <h2 class="tp-ff-poppins tp-text-common-black-4 lh-110-per fs-50 fs-lg-45 fs-sm-40 fw-600 ls-m-2 mb-20">Patient’s share thoughts</h2>
                 </div>
              </div>
           </div>
        </div>
        <div class="tp-testimonial-digi-slide-wrap p-relative fix">
           <span class="tp-testimonial-digi-shadow left med p-absolute"></span>
           <span class="tp-testimonial-digi-shadow right med p-absolute"></span>
           <div class="swiper-container tp-testimonial-med-slide pb-55">
              <div class="swiper-wrapper">
                 <div class="swiper-slide">
                    <div class="tp-testimonial-med-item tp-round-10 d-flex">
                       <div class="tp-testimonial-med-qoute p-relative pr-30">
                          <span class="d-block">
                             <svg width="56" height="40" viewBox="0 0 56 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M56.0001 16H44.0001L52.0002 0H40.0002L32.0001 16V40H56.0001V16Z" fill="black" fill-opacity="0.06" />
                                <path d="M24 40V16H12L20.0001 0H8.00004L-3.43323e-05 16V40H24Z" fill="black" fill-opacity="0.06" />
                             </svg>
                          </span>
                          <span class="ratings tp-ff-roboto fw-600 fs-14 tp-text-common-black-4">4.5 <i class="fa-sharp fa-solid fa-star fs-12 tp-text-common-yellow"></i></span>
                       </div>
                       <div class="tp-testimonial-med">
                          <p class="lh-30 tp-ff-roboto mb-25 tp-text-grey-9">Diagnosy  always help to provide proper treatment for most of get the proper cure  and healty life which is the cooperate  focus thing for us patient our
                             main proprity services  cooperate  focus thing</p>
                          <div class="d-flex align-items-center">
                             <img class="rounded-circle mr-15" src="../assets/img/digital/testimoial/testimonial.png" alt="">
                             <div class="info">
                                <h5 class="tp-ff-poppins tp-text-common-black-4 fw-500 ls-m-2 fs-20 lh-1 mb-5">Jennifer Catlin</h5>
                                <span class="tp-ff-roboto tp-text-grey-9 fs-16">Neurologist</span>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="swiper-slide">
                    <div class="tp-testimonial-med-item tp-round-10 d-flex">
                       <div class="tp-testimonial-med-qoute p-relative pr-30">
                          <span class="d-block">
                             <svg width="56" height="40" viewBox="0 0 56 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M56.0001 16H44.0001L52.0002 0H40.0002L32.0001 16V40H56.0001V16Z" fill="black" fill-opacity="0.06" />
                                <path d="M24 40V16H12L20.0001 0H8.00004L-3.43323e-05 16V40H24Z" fill="black" fill-opacity="0.06" />
                             </svg>
                          </span>
                          <span class="ratings tp-ff-roboto fw-600 fs-14 tp-text-common-black-4">4.5 <i class="fa-sharp fa-solid fa-star fs-12 tp-text-common-yellow"></i></span>
                       </div>
                       <div class="tp-testimonial-med">
                          <p class="lh-30 tp-ff-roboto mb-25 tp-text-grey-9">Diagnosy  always help to provide proper treatment for most of get the proper cure  and healty life which is the cooperate  focus thing for us patient our
                             main proprity services  cooperate  focus thing</p>
                          <div class="d-flex align-items-center">
                             <img class="rounded-circle mr-15" src="../assets/img/digital/testimoial/testimonial-2.png" alt="">
                             <div class="info">
                                <h5 class="tp-ff-poppins tp-text-common-black-4 fw-500 ls-m-2 fs-20 lh-1 mb-5">Richard Vanman</h5>
                                <span class="tp-ff-roboto tp-text-grey-9 fs-16">Neurologist</span>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="swiper-slide">
                    <div class="tp-testimonial-med-item tp-round-10 d-flex">
                       <div class="tp-testimonial-med-qoute p-relative pr-30">
                          <span class="d-block">
                             <svg width="56" height="40" viewBox="0 0 56 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M56.0001 16H44.0001L52.0002 0H40.0002L32.0001 16V40H56.0001V16Z" fill="black" fill-opacity="0.06" />
                                <path d="M24 40V16H12L20.0001 0H8.00004L-3.43323e-05 16V40H24Z" fill="black" fill-opacity="0.06" />
                             </svg>
                          </span>
                          <span class="ratings tp-ff-roboto fw-600 fs-14 tp-text-common-black-4">4.5 <i class="fa-sharp fa-solid fa-star fs-12 tp-text-common-yellow"></i></span>
                       </div>
                       <div class="tp-testimonial-med">
                          <p class="lh-30 tp-ff-roboto mb-25 tp-text-grey-9">Diagnosy  always help to provide proper treatment for most of get the proper cure  and healty life which is the cooperate  focus thing for us patient our
                             main proprity services  cooperate  focus thing</p>
                          <div class="d-flex align-items-center">
                             <img class="rounded-circle mr-15" src="../assets/img/digital/testimoial/testimonial-3.png" alt="">
                             <div class="info">
                                <h5 class="tp-ff-poppins tp-text-common-black-4 fw-500 ls-m-2 fs-20 lh-1 mb-5">Allison George</h5>
                                <span class="tp-ff-roboto tp-text-grey-9 fs-16">Neurologist</span>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="swiper-slide">
                    <div class="tp-testimonial-med-item tp-round-10 d-flex">
                       <div class="tp-testimonial-med-qoute p-relative pr-30">
                          <span class="d-block">
                             <svg width="56" height="40" viewBox="0 0 56 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M56.0001 16H44.0001L52.0002 0H40.0002L32.0001 16V40H56.0001V16Z" fill="black" fill-opacity="0.06" />
                                <path d="M24 40V16H12L20.0001 0H8.00004L-3.43323e-05 16V40H24Z" fill="black" fill-opacity="0.06" />
                             </svg>
                          </span>
                          <span class="ratings tp-ff-roboto fw-600 fs-14 tp-text-common-black-4">4.5 <i class="fa-sharp fa-solid fa-star fs-12 tp-text-common-yellow"></i></span>
                       </div>
                       <div class="tp-testimonial-med">
                          <p class="lh-30 tp-ff-roboto mb-25 tp-text-grey-9">Diagnosy  always help to provide proper treatment for most of get the proper cure  and healty life which is the cooperate  focus thing for us patient our
                             main proprity services  cooperate  focus thing</p>
                          <div class="d-flex align-items-center">
                             <img class="rounded-circle mr-15" src="../assets/img/digital/testimoial/testimonial-4.png" alt="">
                             <div class="info">
                                <h5 class="tp-ff-poppins tp-text-common-black-4 fw-500 ls-m-2 fs-20 lh-1 mb-5">Harshal Mitra</h5>
                                <span class="tp-ff-roboto tp-text-grey-9 fs-16">Neurologist</span>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <!-- tp-testimonial-area-end -->


@endsection()
