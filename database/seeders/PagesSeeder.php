<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {


        DB::table('pages')->insert([
            [
                "id" => 1,
                "page_id" => uniqid(),
                "page_name" => "home",
                "section_name" => "HeroSection",
                "page_slug" => "home",
                "page_content" => <<<'HTML'
                 <section class="relative py-10">                    
                    <div class="overflow-hidden lg:pb-44 ">
                        <div class="container px-4 mx-auto">
                            <div class="flex flex-col md:flex-row ">
                                <div class="w-full md:w-1/2 lg:w-4/12 xl:w-6/12 ">
                                    <h1
                                        class="mb-9 text-6xl md:text-7xl lg:text-7xl font-bold font-heading md:max-w-2xl leading-none">Discover and Book Services</h1>
                                    <div>
                                        <p class="mb-9 text-xl text-gray-900 font-medium md:max-w-sm">Seamlessly find and book services that match your needs, all in one place.</p>
                                        <div
                                            class="mb-2 p-1.5 xl:pl-7 inline-block md:max-w-xl border-2 border-indigo-600 rounded-3xl focus-within:ring focus-within:ring-indigo-300 w-full md:w-2/3">
                                            <div class="flex flex-wrap items-center">
                                                <div class="relative lg:flex-1">
                                                <input id="service-input" 
                                                        type="text"
                                                        class="w-full p-4 md:p-2 lg:p-0 text-lg text-sm placeholder-gray-500 border-none focus:ring-0 focus:outline-none focus:border-none bg-transparent z-20"
                                                        autocomplete="off" 
                                                        autofocus 
                                                    />
                                                    <div id="service-suggestions"
                                                        class="absolute z-50 mt-4 p-4 w-full h-52 overflow-y-auto bg-white rounded-xl shadow-lg hidden">                                                        
                                                    </div>
                                                </div>
                                                <div class="w-full xl:w-auto">
                                                    <div class="block">
                                                        <button
                                                            class="py-4 px-7 w-full text-white font-semibold rounded-2xl focus:ring focus:ring-indigo-300 bg-indigo-600 hover:bg-indigo-700 transition ease-in-out duration-200"
                                                            type="button" id="find-button">Find</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p id="error-message" class="mt-1 font-medium text-red-500 text-lg"></p>
                                        <p class="mb-12 font-medium text-gray-500 z-10">Search for business categories</p>
                                        <h3 class="mb-3 text-gray-900 font-semibold z-10">Trusted by 1M+ customers</h3>
                                        <div class="flex flex-wrap items-center -m-1 z-10">
                                            <div class="w-auto p-1">
                                                <div class="flex flex-wrap -m-0.5">
                                                    <div class="w-auto p-0.5">
                                                        <svg width="17" height="16" viewbox="0 0 17 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.707 1.21267C8.02812 0.224357 9.42632 0.224357 9.74744 1.21267L10.8948 4.74387C11.0384 5.18586 11.4503 5.48511 11.915 5.48511H15.6279C16.6671 5.48511 17.0992 6.81488 16.2585 7.42569L13.2547 9.60809C12.8787 9.88126 12.7214 10.3654 12.865 10.8074L14.0123 14.3386C14.3335 15.327 13.2023 16.1488 12.3616 15.538L9.35775 13.3556C8.98178 13.0824 8.47266 13.0824 8.09669 13.3556L5.09287 15.538C4.25216 16.1488 3.12099 15.327 3.44211 14.3386L4.58947 10.8074C4.73308 10.3654 4.57575 9.88126 4.19978 9.60809L1.19596 7.42569C0.355248 6.81488 0.787317 5.48511 1.82649 5.48511H5.53942C6.00415 5.48511 6.41603 5.18586 6.55964 4.74387L7.707 1.21267Z"
                                                                fill="#6366F1"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="w-auto p-0.5">
                                                        <svg width="17" height="16" viewbox="0 0 17 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.707 1.21267C8.02812 0.224357 9.42632 0.224357 9.74744 1.21267L10.8948 4.74387C11.0384 5.18586 11.4503 5.48511 11.915 5.48511H15.6279C16.6671 5.48511 17.0992 6.81488 16.2585 7.42569L13.2547 9.60809C12.8787 9.88126 12.7214 10.3654 12.865 10.8074L14.0123 14.3386C14.3335 15.327 13.2023 16.1488 12.3616 15.538L9.35775 13.3556C8.98178 13.0824 8.47266 13.0824 8.09669 13.3556L5.09287 15.538C4.25216 16.1488 3.12099 15.327 3.44211 14.3386L4.58947 10.8074C4.73308 10.3654 4.57575 9.88126 4.19978 9.60809L1.19596 7.42569C0.355248 6.81488 0.787317 5.48511 1.82649 5.48511H5.53942C6.00415 5.48511 6.41603 5.18586 6.55964 4.74387L7.707 1.21267Z"
                                                                fill="#6366F1"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="w-auto p-0.5">
                                                        <svg width="17" height="16" viewbox="0 0 17 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.707 1.21267C8.02812 0.224357 9.42632 0.224357 9.74744 1.21267L10.8948 4.74387C11.0384 5.18586 11.4503 5.48511 11.915 5.48511H15.6279C16.6671 5.48511 17.0992 6.81488 16.2585 7.42569L13.2547 9.60809C12.8787 9.88126 12.7214 10.3654 12.865 10.8074L14.0123 14.3386C14.3335 15.327 13.2023 16.1488 12.3616 15.538L9.35775 13.3556C8.98178 13.0824 8.47266 13.0824 8.09669 13.3556L5.09287 15.538C4.25216 16.1488 3.12099 15.327 3.44211 14.3386L4.58947 10.8074C4.73308 10.3654 4.57575 9.88126 4.19978 9.60809L1.19596 7.42569C0.355248 6.81488 0.787317 5.48511 1.82649 5.48511H5.53942C6.00415 5.48511 6.41603 5.18586 6.55964 4.74387L7.707 1.21267Z"
                                                                fill="#6366F1"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="w-auto p-0.5">
                                                        <svg width="17" height="16" viewbox="0 0 17 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.707 1.21267C8.02812 0.224357 9.42632 0.224357 9.74744 1.21267L10.8948 4.74387C11.0384 5.18586 11.4503 5.48511 11.915 5.48511H15.6279C16.6671 5.48511 17.0992 6.81488 16.2585 7.42569L13.2547 9.60809C12.8787 9.88126 12.7214 10.3654 12.865 10.8074L14.0123 14.3386C14.3335 15.327 13.2023 16.1488 12.3616 15.538L9.35775 13.3556C8.98178 13.0824 8.47266 13.0824 8.09669 13.3556L5.09287 15.538C4.25216 16.1488 3.12099 15.327 3.44211 14.3386L4.58947 10.8074C4.73308 10.3654 4.57575 9.88126 4.19978 9.60809L1.19596 7.42569C0.355248 6.81488 0.787317 5.48511 1.82649 5.48511H5.53942C6.00415 5.48511 6.41603 5.18586 6.55964 4.74387L7.707 1.21267Z"
                                                                fill="#6366F1"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="w-auto p-0.5">
                                                        <svg width="17" height="16" viewbox="0 0 17 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.707 1.21267C8.02812 0.224357 9.42632 0.224357 9.74744 1.21267L10.8948 4.74387C11.0384 5.18586 11.4503 5.48511 11.915 5.48511H15.6279C16.6671 5.48511 17.0992 6.81488 16.2585 7.42569L13.2547 9.60809C12.8787 9.88126 12.7214 10.3654 12.865 10.8074L14.0123 14.3386C14.3335 15.327 13.2023 16.1488 12.3616 15.538L9.35775 13.3556C8.98178 13.0824 8.47266 13.0824 8.09669 13.3556L5.09287 15.538C4.25216 16.1488 3.12099 15.327 3.44211 14.3386L4.58947 10.8074C4.73308 10.3654 4.57575 9.88126 4.19978 9.60809L1.19596 7.42569C0.355248 6.81488 0.787317 5.48511 1.82649 5.48511H5.53942C6.00415 5.48511 6.41603 5.18586 6.55964 4.74387L7.707 1.21267Z"
                                                                fill="#6366F1"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-auto p-1">
                                                <div class="flex flex-wrap -m-0.5">
                                                    <div class="w-auto p-0.5">
                                                        <p class="text-gray-900 font-bold">4.2/5</p>
                                                    </div>
                                                    <div class="w-auto p-0.5">
                                                        <p class="text-gray-600 font-medium">(45k Reviews)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2 lg:w-8/12 xl:w-8/12 pt-10 md:pt-0">
                                    <div class="flex flex-wrap justify-center items-center lg:justify-end -m-3 ">
                                        <div class="w-auto lg:w-1/3 xl:pt-28 p-3">
                                            <div class="flex flex-wrap justify-end">
                                                <div class="w-auto">
                                                    <img class="mx-auto transform hover:-translate-y-16 transition ease-in-out duration-1000 hidden md:block"
                                                        src="../../home-assets/images/headers/people.avif" alt="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-auto lg:w-1/3 p-3">
                                            <div class="flex flex-wrap justify-center -m-3">
                                                <div class="w-auto p-3">
                                                    <a href="#">
                                                        <img class="mx-auto transform hover:-translate-y-16 transition ease-in-out duration-1000 hidden md:block"
                                                            src="../../home-assets/images/headers/video.avif" alt="" />
                                                    </a>
                                                </div>
                                                <div class="w-auto p-3">
                                                    <img class="mx-auto transform hover:-translate-y-16 transition ease-in-out duration-1000 hidden md:block"
                                                        src="../../home-assets/images/headers/people2.avif" alt="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-auto lg:w-1/3 p-3">
                                            <div class="flex flex-wrap">
                                                <div class="w-auto">
                                                    <img class="mx-auto transform hover:-translate-y-16 transition ease-in-out duration-1000 hidden md:block"
                                                        src="../../home-assets/images/headers/people3.avif" alt="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>               
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 2,
                "page_id" => uniqid(),
                "page_name" => "home",
                "section_name" => "HighlightsSection",
                "page_slug" => "home",
                "page_content" => <<<'HTML'
                <section class="relative py-24 overflow-hidden">                    
                    <div class="container px-4 mx-auto">
                    <div class="flex flex-wrap -m-8">
                        <div class="w-full md:w-1/2 lg:w-1/4 p-8">
                            <h2 class="text-5xl font-bold font-heading tracking-px-n leading-tight">Bookin: The Ultimate Service Booking Platform</h2>
                        </div>
                        <div class="w-full md:w-1/2 lg:w-1/4 p-8">
                        <div class="md:w-56">
                            <h2 class="mb-3 text-6xl md:text-7xl font-bold font-heading tracking-px-n leading-tight">15k+</h2>
                            <p class="text-lg text-gray-700 font-medium leading-normal">Trusted by over 15k+ service providers and customers.</p>
                        </div>
                        </div>
                        <div class="w-full md:w-1/2 lg:w-1/4 p-8">
                        <div class="md:w-56">
                            <h2 class="mb-3 text-6xl md:text-7xl font-bold font-heading tracking-px-n leading-tight">100k+</h2>
                            <p class="text-lg text-gray-700 font-medium leading-normal">Over 100k+ bookings successfully completed across various services.</p>
                        </div>
                        </div>
                        <div class="w-full md:w-1/2 lg:w-1/4 p-8">
                        <div class="md:w-56">
                            <h2 class="mb-3 text-6xl md:text-7xl font-bold font-heading tracking-px-n leading-tight">95k+</h2>
                            <p class="text-lg text-gray-700 font-medium leading-normal">93k+ positive reviews from satisfied customers.</p>
                        </div>
                        </div>
                    </div>
                    </div>
                </section>              
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 3,
                "page_id" => uniqid(),
                "page_name" => "home",
                "section_name" => "WorkSection",
                "page_slug" => "home",
                "page_content" => <<<'HTML'
                <section class="py-20 bg-white overflow-hidden">
                    <div class="container px-4 mx-auto">
                        <h2 class="mb-5 text-6xl md:text-7xl text-center font-bold font-heading tracking-px-n leading-tight">How Bookin Works</h2>
                        <p class="mb-20 text-lg text-gray-900 text-center font-medium md:max-w-lg mx-auto">Book your preferred service provider in just a few simple steps. From finding experts to booking appointments, we've got you covered.</p>
                        <div class="flex flex-wrap -m-8" >
                            <div class="w-full md:w-1/3 p-8">
                                <div class="relative text-center">
                                    <img class="absolute -right-40 top-8"
                                        src="../../home-assets/images/how-it-works/line4.svg" alt="" />
                                    <div
                                        class="relative w-14 h-14 mb-10 mx-auto text-2xl font-bold font-heading bg-indigo-100 rounded-full">
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <svg width="25" height="24" viewbox="0 0 25 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21.5391 21L15.5391 15M17.5391 10C17.5391 13.866 14.4051 17 10.5391 17C6.67307 17 3.53906 13.866 3.53906 10C3.53906 6.13401 6.67307 3 10.5391 3C14.4051 3 17.5391 6.13401 17.5391 10Z"
                                                    stroke="#111827" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="md:max-w-xs mx-auto">
                                        <h3 class="mb-5 font-heading text-xl font-bold font-heading leading-normal">Find Experts</h3>                                        
                                        <p class="font-sans text-gray-600">Browse through a list of service providers to find the expert that meets your needs.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/3 p-8">
                                <div class="relative text-center">
                                    <img class="absolute -right-40 top-8"
                                        src="../../home-assets/images/how-it-works/line4.svg" alt="" />
                                    <div
                                        class="relative w-14 h-14 mb-10 mx-auto text-2xl font-bold font-heading bg-indigo-600 rounded-full">
                                        <img class="absolute top-0 left-0"
                                            src = "../../home-assets/images/how-it-works/gradient.svg" alt="" />
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17 14V20M14 17H20M6 10H8C9.10457 10 10 9.10457 10 8V6C10 4.89543 9.10457 4 8 4H6C4.89543 4 4 4.89543 4 6V8C4 9.10457 4.89543 10 6 10ZM16 10H18C19.1046 10 20 9.10457 20 8V6C20 4.89543 19.1046 4 18 4H16C14.8954 4 14 4.89543 14 6V8C14 9.10457 14.8954 10 16 10ZM6 20H8C9.10457 20 10 19.1046 10 18V16C10 14.8954 9.10457 14 8 14H6C4.89543 14 4 14.8954 4 16V18C4 19.1046 4.89543 20 6 20Z"
                                                    stroke="white" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="md:max-w-xs mx-auto">
                                        <h3 class="mb-5 font-heading text-xl font-bold font-heading leading-normal">Schedule Your Appointment</h3>
                                        <p class="font-sans text-gray-600">Choose a convenient time and book your appointment directly through our platform.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/3 p-8">
                                <div class="text-center">
                                    <div
                                        class="relative w-14 h-14 mb-10 mx-auto text-2xl font-bold font-heading bg-indigo-100 rounded-full">
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <svg width="25" height="24" viewbox="0 0 25 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.4609 11C11.9087 11 11.4609 11.4477 11.4609 12C11.4609 12.5523 11.9087 13 12.4609 13V11ZM15.4609 13C16.0132 13 16.4609 12.5523 16.4609 12C16.4609 11.4477 16.0132 11 15.4609 11V13ZM12.4609 15C11.9087 15 11.4609 15.4477 11.4609 16C11.4609 16.5523 11.9087 17 12.4609 17V15ZM15.4609 17C16.0132 17 16.4609 16.5523 16.4609 16C16.4609 15.4477 16.0132 15 15.4609 15V17ZM9.46094 11C8.90865 11 8.46094 11.4477 8.46094 12C8.46094 12.5523 8.90865 13 9.46094 13V11ZM9.47094 13C10.0232 13 10.4709 12.5523 10.4709 12C10.4709 11.4477 10.0232 11 9.47094 11V13ZM9.46094 15C8.90865 15 8.46094 15.4477 8.46094 16C8.46094 16.5523 8.90865 17 9.46094 17V15ZM9.47094 17C10.0232 17 10.4709 16.5523 10.4709 16C10.4709 15.4477 10.0232 15 9.47094 15V17ZM18.4609 7V19H20.4609V7H18.4609ZM17.4609 20H7.46094V22H17.4609V20ZM6.46094 19V7H4.46094V19H6.46094ZM7.46094 6H9.46094V4H7.46094V6ZM15.4609 6H17.4609V4H15.4609V6ZM7.46094 20C6.90865 20 6.46094 19.5523 6.46094 19H4.46094C4.46094 20.6569 5.80408 22 7.46094 22V20ZM18.4609 19C18.4609 19.5523 18.0132 20 17.4609 20V22C19.1178 22 20.4609 20.6569 20.4609 19H18.4609ZM20.4609 7C20.4609 5.34315 19.1178 4 17.4609 4V6C18.0132 6 18.4609 6.44772 18.4609 7H20.4609ZM6.46094 7C6.46094 6.44772 6.90865 6 7.46094 6V4C5.80408 4 4.46094 5.34315 4.46094 7H6.46094ZM12.4609 13H15.4609V11H12.4609V13ZM12.4609 17H15.4609V15H12.4609V17ZM11.4609 4H13.4609V2H11.4609V4ZM13.4609 6H11.4609V8H13.4609V6ZM11.4609 6C10.9087 6 10.4609 5.55228 10.4609 5H8.46094C8.46094 6.65685 9.80408 8 11.4609 8V6ZM14.4609 5C14.4609 5.55228 14.0132 6 13.4609 6V8C15.1178 8 16.4609 6.65685 16.4609 5H14.4609ZM13.4609 4C14.0132 4 14.4609 4.44772 14.4609 5H16.4609C16.4609 3.34315 15.1178 2 13.4609 2V4ZM11.4609 2C9.80408 2 8.46094 3.34315 8.46094 5H10.4609C10.4609 4.44772 10.9087 4 11.4609 4V2ZM9.46094 13H9.47094V11H9.46094V13ZM9.46094 17H9.47094V15H9.46094V17Z"
                                                    fill="#111827"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="md:max-w-xs mx-auto">
                                        <h3 class="mb-5 font-heading text-xl font-bold font-heading leading-normal">Enjoy Seamless Service</h3>
                                        <p class="font-sans text-gray-600">Relax and let the professionals handle your needs, ensuring a top-notch experience every time.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>                    
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 4,
                "page_id" => uniqid(),
                "page_name" => "home",
                "section_name" => "ReviewSection",
                "page_slug" => "home",
                "page_content" => <<<'HTML'
                <section class="relative py-24 bg-white overflow-hidden">                    
                    <div class="relative z-10 container px-4 mx-auto">
                        <div class="flex flex-wrap justify-between items-end -m-2 mb-12">
                            <div class="w-auto p-2">
                                <h2 class="text-6xl md:text-7xl font-bold font-heading tracking-px-n leading-tight">Customers love using Bookin.</h2>
                            </div>                            
                        </div>
                        <div class="flex flex-wrap -m-2">
                            <div class="w-full md:w-1/2 lg:w-1/4 p-2">
                                <div class="px-8 py-6 h-full bg-white bg-opacity-80 border rounded-xl">
                                    <div class="flex flex-col justify-between h-full">
                                        <div class="mb-7 block">
                                            <div class="flex flex-wrap -m-0.5 mb-6">
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <h3 class="mb-6 text-lg font-bold font-heading">“We love it.”</h3>
                                            <p class="text-lg font-medium">Bookin Website has transformed the way we manage our bookings. It's incredibly user-friendly. Highly recommend trying it out!</p>
                                        </div>
                                        <div class="block">
                                            <p class="font-bold">Jenny Wilson - Bookin User</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 lg:w-1/4 p-2">
                                <div class="px-8 py-6 h-full bg-white bg-opacity-80 border rounded-xl">
                                    <div class="flex flex-col justify-between h-full">
                                        <div class="mb-7 block">
                                            <div class="flex flex-wrap -m-0.5 mb-6">
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <h3 class="mb-6 text-lg font-bold font-heading">“Effortless Customer Management”</h3>
                                            <p class="text-lg font-medium">Bookin’s customer management service is designed to simplify your workflow. Keep all your important information at your fingertips, accessible from any device, whenever you need it.</p>
                                        </div>
                                        <div class="block">
                                            <p class="font-bold">John Doe - Bookin User</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 lg:w-1/4 p-2">
                                <div class="px-8 py-6 h-full bg-white bg-opacity-80 border rounded-xl">
                                    <div class="flex flex-col justify-between h-full">
                                        <div class="mb-7 block">
                                            <div class="flex flex-wrap -m-0.5 mb-6">
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <h3 class="mb-6 text-lg font-bold font-heading">“Excellent Support”</h3>
                                            <p class="text-lg font-medium">The support team at Bookin is phenomenal! They are quick to respond and truly understand our needs.</p>
                                        </div>
                                        <div class="block">
                                            <p class="font-bold">Hailey - Bookin User</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 lg:w-1/4 p-2">
                                <div class="px-8 py-6 h-full bg-white bg-opacity-80 border rounded-xl">
                                    <div class="flex flex-col justify-between h-full">
                                        <div class="mb-7 block">
                                            <div class="flex flex-wrap -m-0.5 mb-6">
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                                <div class="w-auto p-0.5">
                                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.30769 0L12.1838 5.82662L18.6154 6.76111L13.9615 11.2977L15.0598 17.7032L9.30769 14.6801L3.55554 17.7032L4.65385 11.2977L0 6.76111L6.43162 5.82662L9.30769 0Z"
                                                            fill="#F59E0B"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <h3 class="mb-6 text-lg font-bold font-heading">“Time-saving and reliable”</h3>
                                            <p class="text-lg font-medium">Bookin has saved us so much time in scheduling and organization. It’s a reliable tool that makes complex bookings feel easy. From layout to functionality, it covers everything we need.</p>
                                        </div>
                                        <div class="block">
                                            <p class="font-bold">Jenifer - Bookin User</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>              
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 5,
                "page_id" => uniqid(),
                "page_name" => "home",
                "section_name" => "FAQSection",
                "page_slug" => "home",
                "page_content" => <<<'HTML'
                <section class="py-28 bg-white overflow-hidden" id="faq">
                    <div class="container px-4 mx-auto">
                        <div class="flex flex-wrap -m-8">
                            <div class="w-full md:w-1/2 p-8">
                                <div class="md:max-w-md">
                                    <h2 class="mb-7 text-6xl md:text-7xl font-bold font-heading tracking-px-n leading-tight">Frequently Asked Questions</h2>
                                    <p class="mb-11 text-gray-600 font-medium leading-relaxed">Have questions? We’ve got answers! Below are some of the most common questions we receive about Bookin. If you don't find what you're looking for, feel free to reach out to our support team.</p>                                    
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 p-8">
                                <div class="md:max-w-2xl ml-auto">
                                    <div class="flex flex-wrap">
                                        <div class="w-full">
                                            <a x-data="{ accordion: false }" x-on:click.prevent="accordion = !accordion"
                                                class="block border-b border-gray-300" href="#">
                                                <div class="flex flex-wrap justify-between py-7 -m-1.5">
                                                    <div class="flex-1 p-1.5">
                                                        <div class="flex flex-wrap -m-1.5">
                                                            <div class="w-auto p-1.5">
                                                                <svg class="relative top-1" width="15" height="15"
                                                                    viewbox="0 0 15 15" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5.14229 5.625C5.48549 4.89675 6.41152 4.375 7.50003 4.375C8.88075 4.375 10 5.21447 10 6.25C10 7.12465 9.20152 7.85942 8.12142 8.06662C7.78242 8.13166 7.50003 8.40482 7.50003 8.75M7.5 10.625H7.50625M13.125 7.5C13.125 10.6066 10.6066 13.125 7.5 13.125C4.3934 13.125 1.875 10.6066 1.875 7.5C1.875 4.3934 4.3934 1.875 7.5 1.875C10.6066 1.875 13.125 4.3934 13.125 7.5Z"
                                                                        stroke="#4F46E5" stroke-width="1.5"
                                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 p-1.5">
                                                                <h3 class="font-semibold leading-normal">What is Bookin and how can it help my business?</h3>
                                                                <div x-ref="container"
                                                                    :style="accordion ? 'height: ' + $refs.container.scrollHeight +
                                                                        'px' : ''"
                                                                    class="overflow-hidden h-0 duration-500">
                                                                    <p class="mt-4 mb-5 text-gray-600 font-medium leading-relaxed">Bookin is a comprehensive booking and customer management service that allows you to efficiently schedule appointments, manage customer data, and track interactions in one place. It helps simplify administrative tasks, improve organization, and enhance customer experience by keeping everything secure and accessible.</p>                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-auto p-1.5">
                                                        <div :class="{ 'hidden': !accordion }" class="hidden">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M3.21967 3.21967C3.51256 2.92678 3.98744 2.92678 4.28033 3.21967L7.5 6.43934L10.7197 3.21967C11.0126 2.92678 11.4874 2.92678 11.7803 3.21967C12.0732 3.51256 12.0732 3.98744 11.7803 4.28033L8.56066 7.5L11.7803 10.7197C12.0732 11.0126 12.0732 11.4874 11.7803 11.7803C11.4874 12.0732 11.0126 12.0732 10.7197 11.7803L7.5 8.56066L4.28033 11.7803C3.98744 12.0732 3.51256 12.0732 3.21967 11.7803C2.92678 11.4874 2.92678 11.0126 3.21967 10.7197L6.43934 7.5L3.21967 4.28033C2.92678 3.98744 2.92678 3.51256 3.21967 3.21967Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                        <div :class="{ 'hidden': accordion }">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.5 2.25C7.91421 2.25 8.25 2.58579 8.25 3V6.75H12C12.4142 6.75 12.75 7.08579 12.75 7.5C12.75 7.91421 12.4142 8.25 12 8.25H8.25V12C8.25 12.4142 7.91421 12.75 7.5 12.75C7.08579 12.75 6.75 12.4142 6.75 12V8.25H3C2.58579 8.25 2.25 7.91421 2.25 7.5C2.25 7.08579 2.58579 6.75 3 6.75L6.75 6.75V3C6.75 2.58579 7.08579 2.25 7.5 2.25Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="w-full">
                                            <a x-data="{ accordion: false }" x-on:click.prevent="accordion = !accordion"
                                                class="block border-b border-gray-300" href="#">
                                                <div class="flex flex-wrap justify-between py-7 -m-1.5">
                                                    <div class="flex-1 p-1.5">
                                                        <div class="flex flex-wrap -m-1.5">
                                                            <div class="w-auto p-1.5">
                                                                <svg class="relative top-1" width="15" height="15"
                                                                    viewbox="0 0 15 15" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5.14229 5.625C5.48549 4.89675 6.41152 4.375 7.50003 4.375C8.88075 4.375 10 5.21447 10 6.25C10 7.12465 9.20152 7.85942 8.12142 8.06662C7.78242 8.13166 7.50003 8.40482 7.50003 8.75M7.5 10.625H7.50625M13.125 7.5C13.125 10.6066 10.6066 13.125 7.5 13.125C4.3934 13.125 1.875 10.6066 1.875 7.5C1.875 4.3934 4.3934 1.875 7.5 1.875C10.6066 1.875 13.125 4.3934 13.125 7.5Z"
                                                                        stroke="#4F46E5" stroke-width="1.5"
                                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 p-1.5">
                                                                <h3 class="font-semibold leading-normal">Is Bookin secure?</h3>
                                                                <div x-ref="container"
                                                                    :style="accordion ? 'height: ' + $refs.container.scrollHeight +
                                                                        'px' : ''"
                                                                    class="overflow-hidden h-0 duration-500">
                                                                    <p class="mt-4 mb-5 text-gray-600 font-medium leading-relaxed">Yes, Bookin takes security seriously. We use advanced encryption protocols to ensure that your customer data is safe and accessible only to authorized users. We are committed to keeping your information private and secure, with regular updates and monitoring for any potential vulnerabilities.</p>                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-auto p-1.5">
                                                        <div :class="{ 'hidden': !accordion }" class="hidden">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M3.21967 3.21967C3.51256 2.92678 3.98744 2.92678 4.28033 3.21967L7.5 6.43934L10.7197 3.21967C11.0126 2.92678 11.4874 2.92678 11.7803 3.21967C12.0732 3.51256 12.0732 3.98744 11.7803 4.28033L8.56066 7.5L11.7803 10.7197C12.0732 11.0126 12.0732 11.4874 11.7803 11.7803C11.4874 12.0732 11.0126 12.0732 10.7197 11.7803L7.5 8.56066L4.28033 11.7803C3.98744 12.0732 3.51256 12.0732 3.21967 11.7803C2.92678 11.4874 2.92678 11.0126 3.21967 10.7197L6.43934 7.5L3.21967 4.28033C2.92678 3.98744 2.92678 3.51256 3.21967 3.21967Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                        <div :class="{ 'hidden': accordion }">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.5 2.25C7.91421 2.25 8.25 2.58579 8.25 3V6.75H12C12.4142 6.75 12.75 7.08579 12.75 7.5C12.75 7.91421 12.4142 8.25 12 8.25H8.25V12C8.25 12.4142 7.91421 12.75 7.5 12.75C7.08579 12.75 6.75 12.4142 6.75 12V8.25H3C2.58579 8.25 2.25 7.91421 2.25 7.5C2.25 7.08579 2.58579 6.75 3 6.75L6.75 6.75V3C6.75 2.58579 7.08579 2.25 7.5 2.25Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="w-full">
                                            <a x-data="{ accordion: false }" x-on:click.prevent="accordion = !accordion"
                                                class="block border-b border-gray-300" href="#">
                                                <div class="flex flex-wrap justify-between py-7 -m-1.5">
                                                    <div class="flex-1 p-1.5">
                                                        <div class="flex flex-wrap -m-1.5">
                                                            <div class="w-auto p-1.5">
                                                                <svg class="relative top-1" width="15" height="15"
                                                                    viewbox="0 0 15 15" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5.14229 5.625C5.48549 4.89675 6.41152 4.375 7.50003 4.375C8.88075 4.375 10 5.21447 10 6.25C10 7.12465 9.20152 7.85942 8.12142 8.06662C7.78242 8.13166 7.50003 8.40482 7.50003 8.75M7.5 10.625H7.50625M13.125 7.5C13.125 10.6066 10.6066 13.125 7.5 13.125C4.3934 13.125 1.875 10.6066 1.875 7.5C1.875 4.3934 4.3934 1.875 7.5 1.875C10.6066 1.875 13.125 4.3934 13.125 7.5Z"
                                                                        stroke="#4F46E5" stroke-width="1.5"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 p-1.5">
                                                                <h3 class="font-semibold leading-normal">Can I access Bookin from any device?</h3>
                                                                <div x-ref="container"
                                                                    :style="accordion ? 'height: ' + $refs.container.scrollHeight +
                                                                        'px' : ''"
                                                                    class="overflow-hidden h-0 duration-500">
                                                                    <p class="mt-4 mb-5 text-gray-600 font-medium leading-relaxed">Absolutely! you can access it from any device with an internet connection. Whether you're using a laptop, tablet, or smartphone, you'll have full access to your customer information and booking tools.</p>                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-auto p-1.5">
                                                        <div :class="{ 'hidden': !accordion }" class="hidden">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M3.21967 3.21967C3.51256 2.92678 3.98744 2.92678 4.28033 3.21967L7.5 6.43934L10.7197 3.21967C11.0126 2.92678 11.4874 2.92678 11.7803 3.21967C12.0732 3.51256 12.0732 3.98744 11.7803 4.28033L8.56066 7.5L11.7803 10.7197C12.0732 11.0126 12.0732 11.4874 11.7803 11.7803C11.4874 12.0732 11.0126 12.0732 10.7197 11.7803L7.5 8.56066L4.28033 11.7803C3.98744 12.0732 3.51256 12.0732 3.21967 11.7803C2.92678 11.4874 2.92678 11.0126 3.21967 10.7197L6.43934 7.5L3.21967 4.28033C2.92678 3.98744 2.92678 3.51256 3.21967 3.21967Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                        <div :class="{ 'hidden': accordion }">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.5 2.25C7.91421 2.25 8.25 2.58579 8.25 3V6.75H12C12.4142 6.75 12.75 7.08579 12.75 7.5C12.75 7.91421 12.4142 8.25 12 8.25H8.25V12C8.25 12.4142 7.91421 12.75 7.5 12.75C7.08579 12.75 6.75 12.4142 6.75 12V8.25H3C2.58579 8.25 2.25 7.91421 2.25 7.5C2.25 7.08579 2.58579 6.75 3 6.75L6.75 6.75V3C6.75 2.58579 7.08579 2.25 7.5 2.25Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="w-full">
                                            <a x-data="{ accordion: false }" x-on:click.prevent="accordion = !accordion"
                                                class="block border-b border-gray-300" href="#">
                                                <div class="flex flex-wrap justify-between py-7 -m-1.5">
                                                    <div class="flex-1 p-1.5">
                                                        <div class="flex flex-wrap -m-1.5">
                                                            <div class="w-auto p-1.5">
                                                                <svg class="relative top-1" width="15" height="15"
                                                                    viewbox="0 0 15 15" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5.14229 5.625C5.48549 4.89675 6.41152 4.375 7.50003 4.375C8.88075 4.375 10 5.21447 10 6.25C10 7.12465 9.20152 7.85942 8.12142 8.06662C7.78242 8.13166 7.50003 8.40482 7.50003 8.75M7.5 10.625H7.50625M13.125 7.5C13.125 10.6066 10.6066 13.125 7.5 13.125C4.3934 13.125 1.875 10.6066 1.875 7.5C1.875 4.3934 4.3934 1.875 7.5 1.875C10.6066 1.875 13.125 4.3934 13.125 7.5Z"
                                                                        stroke="#4F46E5" stroke-width="1.5"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 p-1.5">
                                                                <h3 class="font-semibold leading-normal">Does Bookin offer customer support?</h3>
                                                                <div x-ref="container"
                                                                    :style="accordion ? 'height: ' + $refs.container.scrollHeight +
                                                                        'px' : ''"
                                                                    class="overflow-hidden h-0 duration-500">
                                                                    <p class="mt-4 mb-5 text-gray-600 font-medium leading-relaxed">Yes, Bookin offers dedicated customer support to assist you with any questions or issues you may encounter. Our support team is available via email, live chat, or phone to ensure you get the help you need, whenever you need it.</p>                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-auto p-1.5">
                                                        <div :class="{ 'hidden': !accordion }" class="hidden">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M3.21967 3.21967C3.51256 2.92678 3.98744 2.92678 4.28033 3.21967L7.5 6.43934L10.7197 3.21967C11.0126 2.92678 11.4874 2.92678 11.7803 3.21967C12.0732 3.51256 12.0732 3.98744 11.7803 4.28033L8.56066 7.5L11.7803 10.7197C12.0732 11.0126 12.0732 11.4874 11.7803 11.7803C11.4874 12.0732 11.0126 12.0732 10.7197 11.7803L7.5 8.56066L4.28033 11.7803C3.98744 12.0732 3.51256 12.0732 3.21967 11.7803C2.92678 11.4874 2.92678 11.0126 3.21967 10.7197L6.43934 7.5L3.21967 4.28033C2.92678 3.98744 2.92678 3.51256 3.21967 3.21967Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                        <div :class="{ 'hidden': accordion }">
                                                            <svg class="relative top-1" width="15" height="15"
                                                                viewbox="0 0 15 15" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.5 2.25C7.91421 2.25 8.25 2.58579 8.25 3V6.75H12C12.4142 6.75 12.75 7.08579 12.75 7.5C12.75 7.91421 12.4142 8.25 12 8.25H8.25V12C8.25 12.4142 7.91421 12.75 7.5 12.75C7.08579 12.75 6.75 12.4142 6.75 12V8.25H3C2.58579 8.25 2.25 7.91421 2.25 7.5C2.25 7.08579 2.58579 6.75 3 6.75L6.75 6.75V3C6.75 2.58579 7.08579 2.25 7.5 2.25Z"
                                                                    fill="black"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>             
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 6,
                "page_id" => uniqid(),
                "page_name" => "features",
                "section_name" => "Features",
                "page_slug" => "features",
                "page_content" => <<<'HTML'
                <section class="py-36 white overflow-hidden relative">
                    <img class="absolute top-0 left-0" src="../../home-assets/images/headers/gradient4.svg" alt="" />                    
                    <div class="container px-4 mx-auto">
                        <div class="flex flex-wrap -m-8">
                            <div class="w-full md:w-1/2 xl:w-1/3 p-8">
                                <div class="md:max-w-sm">
                                    <h2 class="mb-4 text-6xl md:text-7xl font-bold font-heading tracking-px-n leading-tight">Streamline Your Service Booking with Bookin</h2>
                                    <p class="text-gray-900 font-medium leading-relaxed">Manage all your service bookings effortlessly. Bookin allows you to automate scheduling, reduce no-shows, and deliver a seamless experience for both customers and service providers.</p>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 xl:w-2/3 p-8">
                                <div class="flex flex-wrap justify-end -m-4">
                                    <div class="w-full xl:w-auto p-4">
                                        <div class="xl:max-w-sm h-full">
                                            <div class="px-9 py-8 h-full bg-indigo-600 rounded-3xl shadow-7xl">
                                                <div class="flex flex-col justify-between h-full">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-check">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6"/>
                                                            <path d="M16 3v4"/>
                                                            <path d="M8 3v4"/>
                                                            <path d="M4 11h16"/>
                                                            <path d="M15 19l2 2l4 -4"/>
                                                        </svg>
                                                        <h3 class="mb-5 text-3xl text-white font-bold leading-snug">Easy Service Scheduling</h3>
                                                        <p class="mb-24 text-indigo-300 font-medium">Bookin lets you schedule and manage services with ease. Whether you're booking a cleaning, appointment, or consultation, it’s all simplified in a few clicks.</p>
                                                    </div>
                                                    <a class="inline-flex items-center max-w-max text-white hover:text-gray-200"
                                                        href="/register?type=business">
                                                        <span class="mr-2 font-sans font-medium">Get Started</span>
                                                        <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11 3.75L16.25 9M16.25 9L11 14.25M16.25 9L2.75 9"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full xl:w-auto p-4">
                                        <div class="xl:max-w-sm h-full">
                                            <div class="px-9 py-8 h-full bg-indigo-600 rounded-3xl shadow-7xl">
                                                <div class="flex flex-col justify-between h-full">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-time">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4"/>
                                                            <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/>
                                                            <path d="M15 3v4"/>
                                                            <path d="M7 3v4"/>
                                                            <path d="M3 11h16"/>
                                                            <path d="M18 16.496v1.504l1 1"/>
                                                        </svg>
                                                        <h3 class="mb-5 text-3xl text-white font-bold leading-snug">24/7 Platform Availability</h3>
                                                        <p class="mb-24 text-indigo-300 font-medium">With Bookin, your service can be booked anytime, from anywhere. Let your clients schedule appointments at their convenience, even outside regular business hours.</p>
                                                    </div>
                                                    <a class="inline-flex items-center max-w-max text-white hover:text-gray-200"
                                                        href="/register?type=business">
                                                        <span class="mr-2 font-sans font-medium">Get Started</span>
                                                        <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11 3.75L16.25 9M16.25 9L11 14.25M16.25 9L2.75 9"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </section>             
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 7,
                "page_id" => uniqid(),
                "page_name" => "contact",
                "section_name" => "Contact",
                "page_slug" => "contact",
                "page_content" => <<<'HTML'
                <section class="relative py-36 bg-gray-50 overflow-hidden">
                    <img class="absolute bottom-0 right-0" src="../../home-assets/images/contact/gradient4.svg" alt="">
                    <div class="relative z-10 container px-8 mx-auto">
                        <div class="flex flex-wrap -m-8">
                            <div class="w-full md:w-1/2 p-8">
                                <div class="flex flex-col justify-between h-full">
                                    <div class="w-full block">
                                        <h2
                                            class="text-6xl md:text-7xl xl:text-7xl font-bold font-heading tracking-px-n leading-none">Get connected to grow better business.</h2>
                                        <p class="mt-6 text-gray-500 font-medium leading-relaxed text-justify text-xl">Thank you for visiting our website! We are here to assist you with any questions, concerns, or feedback you may have. Whether you're looking for support or just want to get in touch, feel free to reach out to us. Your experience is important to us, and we’re always ready to help you in any way we can. We look forward to hearing from you!</p>
                                        <div class="block mt-8">
                                            <p class="mb-4 text-sm text-gray-400 font-bold uppercase tracking-px text-xl">Email</p>
                                            <ul class="mb-14">
                                                <li class="text-xl font-semibold leading-normal"><a
                                                        href="mailto:info@gmail.com">info@gmail.com</a></li>
                                                <li class="text-xl font-semibold leading-normal"><a
                                                        href="mailto:support@gmail.com">support@gmail.com</a></li>
                                            </ul>
                                            <p class="mb-4 text-sm text-gray-400 font-bold uppercase tracking-px text-xl">Phone</p>
                                            <ul>
                                                <li class="text-xl font-semibold leading-normal"><a href="tel:9876543210">+91 9876543210</a></li>
                                                <li class="text-xl font-semibold leading-normal"><a href="tel:9876543210">+91 9876512345</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full md:w-6/12 p-8"><img src="../../home-assets/images/contact/contact.png"
                                    class="w-full h-full rounded-3xl shadow-md object-cover" />
                            </div>
                        </div>
                    </div>
                </section>
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 8,
                "page_id" => uniqid(),
                "page_name" => "about",
                "section_name" => "About",
                "page_slug" => "about",
                "page_content" => <<<'HTML'
                <section class="relative py-28 bg-gray-50 overflow-hidden">
                    <img class="absolute bottom-0 right-0" src="../../home-assets/images/contact/gradient4.svg" alt="">
                    <div class="relative z-10 container px-4 mx-auto">
                        <div class="flex flex-wrap -m-8">                    
                            <div class="w-full md:w-1/2 p-8">
                                <img class="transform h-full w-full object-cover hover:scale-105 transition ease-in-out duration-1000 rounded-3xl" src="../../home-assets/images/contact/man.png" alt="">
                            </div>
                            <div class="w-full md:w-1/2 p-8">
                                <div class="flex flex-col h-full">
                                <div class="w-full block">                          
                                    <h2 class="text-6xl md:text-7xl xl:text-7xl font-bold font-heading tracking-px-n leading-none">About Us.</h2>
                            </div>
                            <div class="container mx-auto px-4 py-8">                            
                                <div class="mb-3">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Who We Are</h2>
                                    <p class="text-gray-500 text-lg leading-relaxed text-justify">Welcome to Bookin, your trusted partner in simplifying service booking experiences. We are a forward-thinking technology company passionate about creating seamless solutions that connect customers with service providers.Our mission is to empower businesses of all sizes to thrive by delivering exceptional tools that save time and enhance efficiency.</p>
                                </div>
                                <div class="mb-4">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Our Mission</h2>
                                    <p class="text-gray-500 text-lg leading-relaxed text-justify">At Bookin, we believe in transforming how people book and manage services. We aim to provide a platform where convenience meets innovation, enabling businesses to deliver unparalleled experiencesto their customers while streamlining their operations.</p>
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-800 mb-2">What We Offer</h2>
                                    <p class="text-gray-500 text-lg leading-relaxed text-justify">Whether you're managing a salon, spa, pet grooming service, or any other service-oriented business, our platform is designed with you in mind.With user-friendly tools for scheduling, customer management, and payment processing, we make running your business smoother than ever. Customers benefit from effortless booking, personalized recommendations, and real-time updates.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </section>
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 9,
                "page_id" => uniqid(),
                "page_name" => "privacy-policy",
                "section_name" => "Privacy",
                "page_slug" => "privacy-policy",
                "page_content" => <<<'HTML'
                <section class="relative py-20 overflow-hidden px-3 md:px-10 lg:px-24">
                    <img class="absolute top-0 left-0" src="../../home-assets/images/contact/gradient4.svg" alt="">
                    <div class="relative z-10 container px-4 mx-auto">
                        <div class="flex flex-wrap -m-8">                    
                            <div class="w-full p-8">
                                <div class="flex flex-col h-full">
                                    <div class="mb-7 w-full block">                          
                                        <h2 class="text-6xl font-bold font-heading leading-none">Privacy Policy</h2>
                                        <p class="tracking-px text-justify text-xl text-gray-500 py-3">Thank you for choosing Bookin and trusting us with your personal information. This Privacy Policy outlines how we collect, use, share, and protect your information when you use our services.</p>
                                    </div>
                                    <div class="block">
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">1. Information Collection:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">We may collect personal information such as your name, email address, and other relevant details when you voluntarily provide it to us while using our website or services.</p>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">2. How We Use Your Information:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">We use the information collected to provide and improve our services, personalize your experience, communicate with you, and send updates about our products and services. We do not sell or share your personal information with third parties for marketing purposes.</p>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">3. Information Security:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">We take the security of your personal information seriously. We implement reasonable security measures to protect against unauthorized access, disclosure, alteration, or destruction of your information.</p>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">4. Cookies:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">We may use cookies to enhance your user experience. You can choose to disable cookies through your browser settings, but please note that some features of our website may not function properly as a result.</p>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">5. Third-Party Links:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">Our website may contain links to third-party websites. Please be aware that we are not responsible for the privacy practices of these external sites. We encourage you to read the privacy policies of these websites when you leave our site.</p>
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">6. Children's Privacy:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">Our services are not intended for individuals under the age of 13. We do not knowingly collect personal information from children. If you are a parent or guardian and believe your child has provided us with personal information, please contact us, and we will take steps to remove such information.</p>
                                        </div>                                        
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">7. Changes to This Privacy Policy:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page. We recommend checking this page periodically to stay informed about how we are protecting and using your information.</p>                                        
                                        </div>
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold">8. Contact Us:</h3>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-1">If you have any questions or concerns about our refund policy, please contact us at <a class="text-blue-400" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a></p>
                                            <p class="tracking-px text-justify text-xl text-gray-500 py-6">By using our website or services, you agree to the terms outlined in this Privacy Policy.</p>
                                            <p class="tracking-px text-justify text-xl text-gray-500">Thank you for choosing Bookin.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 10,
                "page_id" => uniqid(),
                "page_name" => "refund-policy",
                "section_name" => "Refund",
                "page_slug" => "refund-policy",
                "page_content" => <<<'HTML'
                <section class="relative py-20 overflow-hidden px-3 md:px-10 lg:px-24">
                    <img
                        class="absolute top-0 left-0"
                        src="../../home-assets/images/contact/gradient4.svg"
                        alt=""
                    />
                    <div class="relative z-10 container px-4 mx-auto">
                        <div class="flex flex-wrap -m-8">
                        <div class="w-full p-8">
                            <div class="flex flex-col h-full">
                            <div class="mb-12 w-full block">
                                <h2 class="text-6xl font-bold font-heading leading-none">Refund Policy</h2>
                            </div>
                            <div class="block">
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">1. Eligibility for Refunds:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">Refunds are available for cancellations made 24 hours prior to the scheduled appointment. If you cancel within this time frame, you will receive a full refund.</p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">2. Process for Refunds:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">To initiate a refund, please contact our customer service with your booking details. Make sure to include your name, booking reference, and reason for cancellation.</p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">3. Processing Time:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">Refund requests will be processed within 7 business days from the date of approval. Please note that the time taken for the funds to reflect in your account may vary.</p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">4. No-Show Policy:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">If you do not show up for your appointment and have not provided prior notice, you will not be eligible for a refund.</p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">5. Service Changes:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">If a service provider changes or cancels your appointment, you may request a full refund or choose an alternative date.</p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">5. Exceptions:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">Refunds will not be granted if the user violates our terms of service. Refunds are not applicable for subscription services beyond the initial trial period.</p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">6. Contact Us:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">If you have any questions or concerns about our refund policy, please contact us at <a class="text-blue-400" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a></p>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-6">By using our services, you agree to the terms outlined in this Refund Policy.</p>
                                    <p class="tracking-px text-justify text-xl text-gray-500">Thank you for choosing Bookin.</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </section>
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
            [
                "id" => 11,
                "page_id" => uniqid(),
                "page_name" => "terms-and-conditions",
                "section_name" => "Terms",
                "page_slug" => "terms-and-conditions",
                "page_content" => <<<'HTML'
                <section class="relative py-20 overflow-hidden px-3 md:px-10 lg:px-24">
                    <img
                        class="absolute top-0 left-0"
                        src="../../home-assets/images/contact/gradient4.svg"
                        alt=""
                    />
                    <div class="relative z-10 container px-4 mx-auto">
                        <div class="flex flex-wrap -m-8">
                        <div class="w-full p-8">
                            <div class="flex flex-col h-full">
                            <div class="mb-7 w-full block">
                                <h2 class="text-6xl font-bold font-heading leading-none">Terms and Conditions</h2>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-2">Thank you for choosing Bookin. Please read these Terms and Conditions carefully before using our services.</p>
                            </div>
                            <div class="block">
                                <div class="mb-4">
                                <h3 class="text-2xl font-bold">1. Acceptance of Terms:</h3>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-1">By accessing or using Bookin, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our services.</p>
                                </div>
                                <div class="mb-4">
                                <h3 class="text-2xl font-bold">2. Use of Services:</h3>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-1">You may use our services for personal or business purposes, as intended by Bookin. You agree not to engage in any activity that interferes with or disrupts the functionality of our platform.</p>
                                </div>
                                <div class="mb-4">
                                <h3 class="text-2xl font-bold">3. Intellectual Property:</h3>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-1">The content, features, and functionality of Bookin are the property of Bookin and are protected by copyright, trademark, and other intellectual property laws. You may not reproduce, distribute, modify, or create derivative works without our express consent.</p>
                                </div>
                                <div class="mb-4">
                                <h3 class="text-2xl font-bold">4. User Accounts:</h3>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-1">To access certain features, you may be required to create an account. You are responsible for maintaining the confidentiality of your account information and agree to notify us immediately of any unauthorized use.</p>
                                </div>
                                <div class="mb-4">
                                <h3 class="text-2xl font-bold">5. Privacy Policy:</h3>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-1">Your use of Bookin is also governed by our Privacy Policy . By using our services, you consent to the terms outlined in the Privacy Policy.</p>
                                </div>
                                <div class="mb-4">
                                <h3 class="text-2xl font-bold">6. Refund Policy:</h3>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-1">Our Refund Policy outlines the conditions under which refunds may be granted. Please review our Refund Policy for more details.</p>
                                </div>            
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">7. Support:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">For any inquiries, concerns, or support-related issues, please contact our customer support team at <a class="text-blue-400" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a></p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">8. Changes to Terms:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">We reserve the right to update or modify these Terms and Conditions at any time. The date of the latest update will be displayed at the beginning of this document. It is your responsibility to review these terms periodically.</p>
                                </div>
                                <div class="mb-4">
                                    <h3 class="text-2xl font-bold">9. Termination:</h3>
                                    <p class="tracking-px text-justify text-xl text-gray-500 py-1">We reserve the right to terminate or suspend your access to Bookin at our discretion, without prior notice, for any violation of these Terms and Conditions.</p>
                                </div>
                                <div class="mb-4">
                                <h3 class="text-2xl font-bold">10. Governing Law:</h3>
                                <p class="tracking-px text-justify text-xl text-gray-500 py-1">These Terms and Conditions are governed by and construed in accordance with the laws of India.</p>              
                                <p class="tracking-px text-justify text-xl text-gray-500 py-6">Thank you for choosing Bookin.</p>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </section>
                HTML,
                "meta_title" => "Bookin SaaS - Multi Vendor Service Booking System | Simplify Online Bookings",
                "meta_description" => "Bookin SaaS is a powerful multi-vendor service booking system designed for seamless online appointments and service management. Perfect for businesses offering flexible scheduling, vendor management, and user-friendly experiences.",
                "meta_keywords" => "bookin saas, multi-vendor booking system, bookin saas system, bookin saas platform, multi-vendor service booking, online booking system, bookin saas software, vendor booking platform, multi-vendor scheduling system, service management with bookin saas",
            ],
        ]);
    }
}
