@extends('layouts.global')

@section('title', 'HealthCard BD - আপনার স্বাস্থ্য সেবা সহচর')
@section('subtitle', 'ডিজিটাল স্বাস্থ্য সেবা এখন আপনার হাতের মুঠোয়')

@section('content')
<div class="space-y-16 tiro">

    @include('student.partial.hero')

    {{-- ===========================
         ABOUT HEALTHCARD SERVICE
    ============================ --}}
    <section id="about" class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid md:grid-cols-2 gap-0">
            <div class="p-12 bg-gradient-to-br from-gray-50 to-white">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 {{ detectLanguageClass('HealthCard BD সম্পর্কে') }}">HealthCard BD সম্পর্কে</h2>
                <div class="space-y-6 text-gray-700 leading-relaxed">
                    <p class="text-lg {{ detectLanguageClass('HealthCard BD হল বাংলাদেশের প্রথম সম্পূর্ণ ডিজিটাল স্বাস্থ্য কার্ড ও টেলিমেডিসিন সেবা প্রদানকারী প্ল্যাটফর্ম। আমরা ২০২৩ সাল থেকে দেশব্যাপী মানসম্মত স্বাস্থ্য সেবা প্রদান করে আসছি।') }}">
                        HealthCard BD হল বাংলাদেশের প্রথম সম্পূর্ণ ডিজিটাল স্বাস্থ্য কার্ড ও টেলিমেডিসিন সেবা প্রদানকারী প্ল্যাটফর্ম। 
                        আমরা ২০২৩ সাল থেকে দেশব্যাপী মানসম্মত স্বাস্থ্য সেবা প্রদান করে আসছি।
                    </p>
                    
                    <div class="bg-blue-50 p-6 rounded-2xl border-l-4 border-blue-500">
                        <h3 class="font-semibold text-blue-800 mb-2 {{ detectLanguageClass('আমাদের ভিশন') }}">আমাদের ভিশন</h3>
                        <p class="text-blue-700 {{ detectLanguageClass('প্রতিটি বাংলাদেশীর দোরগোড়ায় সহজলভ্য ও মানসম্মত ডিজিটাল স্বাস্থ্য সেবা পৌঁছে দেওয়া') }}">
                            প্রতিটি বাংলাদেশীর দোরগোড়ায় সহজলভ্য ও মানসম্মত ডিজিটাল স্বাস্থ্য সেবা পৌঁছে দেওয়া
                        </p>
                    </div>

                    <div class="bg-green-50 p-6 rounded-2xl border-l-4 border-green-500">
                        <h3 class="font-semibold text-green-800 mb-2 {{ detectLanguageClass('আমাদের মিশন') }}">আমাদের মিশন</h3>
                        <p class="text-green-700 {{ detectLanguageClass('টেকনোলজির মাধ্যমে স্বাস্থ্য সেবাকে সহজ, দ্রুত ও সাশ্রয়ী করা') }}">
                            টেকনোলজির মাধ্যমে স্বাস্থ্য সেবাকে সহজ, দ্রুত ও সাশ্রয়ী করা
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-100 to-green-100 p-8 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-6xl mb-4">🏥💻</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 {{ detectLanguageClass('ডিজিটাল হেলথকার্ড') }}">ডিজিটাল হেলথকার্ড</h3>
                    <p class="text-gray-600 {{ detectLanguageClass('আপনার সমস্ত স্বাস্থ্য তথ্য এখন একটি কার্ডে') }}">আপনার সমস্ত স্বাস্থ্য তথ্য এখন একটি কার্ডে</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===========================
         SERVICES SECTION
    ============================ --}}
    <section class="bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl p-12 shadow-xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 {{ detectLanguageClass('আমাদের সেবাসমূহ') }}">আমাদের সেবাসমূহ</h2>
            <p class="text-gray-600 text-lg {{ detectLanguageClass('একটি প্ল্যাটফর্মে সমস্ত স্বাস্থ্য সেবা') }}">একটি প্ল্যাটফর্মে সমস্ত স্বাস্থ্য সেবা</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-3xl mb-4 text-blue-500">🩺</div>
                <h3 class="font-semibold text-gray-800 text-lg mb-2 {{ detectLanguageClass('ডিজিটাল হেলথকার্ড') }}">ডিজিটাল হেলথকার্ড</h3>
                <p class="text-gray-600 text-sm {{ detectLanguageClass('আপনার সমস্ত মেডিকেল রেকর্ড, ভ্যাকসিনেশন হিস্ট্রি এবং স্বাস্থ্য তথ্য একটি ডিজিটাল কার্ডে সংরক্ষণ করুন') }}">
                    আপনার সমস্ত মেডিকেল রেকর্ড, ভ্যাকসিনেশন হিস্ট্রি এবং স্বাস্থ্য তথ্য একটি ডিজিটাল কার্ডে সংরক্ষণ করুন
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-3xl mb-4 text-green-500">💻</div>
                <h3 class="font-semibold text-gray-800 text-lg mb-2 {{ detectLanguageClass('টেলিমেডিসিন') }}">টেলিমেডিসিন</h3>
                <p class="text-gray-600 text-sm {{ detectLanguageClass('বাড়িতে বসেই বিশেষজ্ঞ ডাক্তারের সাথে ভিডিও কনসাল্টেশন এবং প্রেসক্রিপশন সংগ্রহ করুন') }}">
                    বাড়িতে বসেই বিশেষজ্ঞ ডাক্তারের সাথে ভিডিও কনসাল্টেশন এবং প্রেসক্রিপশন সংগ্রহ করুন
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-3xl mb-4 text-purple-500">📱</div>
                <h3 class="font-semibold text-gray-800 text-lg mb-2 {{ detectLanguageClass('মোবাইল হেলথ রেকর্ড') }}">মোবাইল হেলথ রেকর্ড</h3>
                <p class="text-gray-600 text-sm {{ detectLanguageClass('আপনার মোবাইল ফোনে সমস্ত স্বাস্থ্য রেকর্ড সংরক্ষণ ও অ্যাক্সেস করার সুবিধা') }}">
                    আপনার মোবাইল ফোনে সমস্ত স্বাস্থ্য রেকর্ড সংরক্ষণ ও অ্যাক্সেস করার সুবিধা
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-3xl mb-4 text-red-500">🚑</div>
                <h3 class="font-semibold text-gray-800 text-lg mb-2 {{ detectLanguageClass('ইমার্জেন্সি সার্ভিস') }}">ইমার্জেন্সি সার্ভিস</h3>
                <p class="text-gray-600 text-sm {{ detectLanguageClass('জরুরি অবস্থায় নিকটস্থ হাসপাতাল ও অ্যাম্বুলেন্স সার্ভিসে দ্রুত অ্যাক্সেস') }}">
                    জরুরি অবস্থায় নিকটস্থ হাসপাতাল ও অ্যাম্বুলেন্স সার্ভিসে দ্রুত অ্যাক্সেস
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-3xl mb-4 text-yellow-500">💊</div>
                <h3 class="font-semibold text-gray-800 text-lg mb-2 {{ detectLanguageClass('অনলাইন প্রেস্ক্রিপশন') }}">অনলাইন প্রেস্ক্রিপশন</h3>
                <p class="text-gray-600 text-sm {{ detectLanguageClass('প্রেসক্রিপশন অনুযায়ী ওষুধ বাড়িতে পৌঁছে দেওয়ার সার্ভিস') }}">
                    প্রেসক্রিপশন অনুযায়ী ওষুধ বাড়িতে পৌঁছে দেওয়ার সার্ভিস
                </p>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-3xl mb-4 text-indigo-500">📊</div>
                <h3 class="font-semibold text-gray-800 text-lg mb-2 {{ detectLanguageClass('হেলথ অ্যানালিটিক্স') }}">হেলথ অ্যানালিটিক্স</h3>
                <p class="text-gray-600 text-sm {{ detectLanguageClass('আপনার স্বাস্থ্য ডেটা বিশ্লেষণ করে ব্যক্তিগতকৃত স্বাস্থ্য পরামর্শ প্রদান') }}">
                    আপনার স্বাস্থ্য ডেটা বিশ্লেষণ করে ব্যক্তিগতকৃত স্বাস্থ্য পরামর্শ প্রদান
                </p>
            </div>
        </div>
    </section>

    {{-- ===========================
         TELEMEDICINE SECTION
    ============================ --}}
    <section class="grid md:grid-cols-2 gap-8">
        {{-- Telemedicine Features --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800 {{ detectLanguageClass('টেলিমেডিসিন সেবা') }}">💻 টেলিমেডিসিন সেবা</h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold inter">
                    ২৪/৭ উপলব্ধ
                </span>
            </div>
            <div class="space-y-6">
                <div class="group border-l-4 border-blue-500 pl-6 py-4 hover:bg-blue-50 rounded-r-2xl transition-all duration-300">
                    <h3 class="font-semibold text-gray-800 text-lg mb-2 group-hover:text-blue-700 transition-colors {{ detectLanguageClass('ভিডিও কনসাল্টেশন') }}">
                        ভিডিও কনসাল্টেশন
                    </h3>
                    <p class="text-gray-600 mb-3 leading-relaxed {{ detectLanguageClass('বিশেষজ্ঞ ডাক্তারের সাথে সরাসরি ভিডিও কলে পরামর্শ নিন। কোনো অপেক্ষা নেই, কোনো ভিড় নেই।') }}">
                        বিশেষজ্ঞ ডাক্তারের সাথে সরাসরি ভিডিও কলে পরামর্শ নিন। কোনো অপেক্ষা নেই, কোনো ভিড় নেই।
                    </p>
                </div>

                <div class="group border-l-4 border-green-500 pl-6 py-4 hover:bg-green-50 rounded-r-2xl transition-all duration-300">
                    <h3 class="font-semibold text-gray-800 text-lg mb-2 group-hover:text-green-700 transition-colors {{ detectLanguageClass('ডিজিটাল প্রেসক্রিপশন') }}">
                        ডিজিটাল প্রেসক্রিপশন
                    </h3>
                    <p class="text-gray-600 mb-3 leading-relaxed {{ detectLanguageClass('কনসাল্টেশনের পরই ইমেইল ও এসএমএসের মাধ্যমে প্রেসক্রিপশন পেয়ে যান।') }}">
                        কনসাল্টেশনের পরই ইমেইল ও এসএমএসের মাধ্যমে প্রেসক্রিপশন পেয়ে যান।
                    </p>
                </div>

                <div class="group border-l-4 border-purple-500 pl-6 py-4 hover:bg-purple-50 rounded-r-2xl transition-all duration-300">
                    <h3 class="font-semibold text-gray-800 text-lg mb-2 group-hover:text-purple-700 transition-colors {{ detectLanguageClass('ই-ফার্মেসি সার্ভিস') }}">
                        ই-ফার্মেসি সার্ভিস
                    </h3>
                    <p class="text-gray-600 mb-3 leading-relaxed {{ detectLanguageClass('প্রেসক্রিপশন অনুযায়ী ওষুধ বাড়িতে ডেলিভারি সার্ভিস। সহজ, নিরাপদ ও নির্ভরযোগ্য।') }}">
                        প্রেসক্রিপশন অনুযায়ী ঔষধ দেওয়া হয়।
                    </p>
                </div>
            </div>
        </div>

        {{-- How It Works --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800 {{ detectLanguageClass('কিভাবে কাজ করে') }}">⚡ কিভাবে কাজ করে</h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold inter">
                    ৪টি সহজ ধাপ
                </span>
            </div>
            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm flex-shrink-0 inter">১</div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1 {{ detectLanguageClass('রেজিস্ট্রেশন করুন') }}">রেজিস্ট্রেশন করুন</h3>
                        <p class="text-gray-600 text-sm {{ detectLanguageClass('আপনার মোবাইল নম্বর দিয়ে সহজেই রেজিস্ট্রেশন সম্পন্ন করুন') }}">আপনার মোবাইল নম্বর দিয়ে সহজেই রেজিস্ট্রেশন সম্পন্ন করুন</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm flex-shrink-0 inter">২</div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1 {{ detectLanguageClass('ডাক্তার নির্বাচন করুন') }}">ডাক্তার নির্বাচন করুন</h3>
                        <p class="text-gray-600 text-sm {{ detectLanguageClass('প্রয়োজন অনুযায়ী বিশেষজ্ঞ ডাক্তার নির্বাচন করুন') }}">প্রয়োজন অনুযায়ী বিশেষজ্ঞ ডাক্তার নির্বাচন করুন</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-purple-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm flex-shrink-0 inter">৩</div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1 {{ detectLanguageClass('কনসাল্টেশন করুন') }}">কনসাল্টেশন করুন</h3>
                        <p class="text-gray-600 text-sm {{ detectLanguageClass('ভিডিও কলের মাধ্যমে ডাক্তারের সাথে কথা বলুন') }}">ভিডিও কলের মাধ্যমে ডাক্তারের সাথে কথা বলুন</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm flex-shrink-0 inter">৪</div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1 {{ detectLanguageClass('প্রেসক্রিপশন পান') }}">প্রেসক্রিপশন পান</h3>
                        <p class="text-gray-600 text-sm {{ detectLanguageClass('ডিজিটাল প্রেসক্রিপশন ও ওষুধ ডেলিভারি সার্ভিস পান') }}">ডিজিটাল প্রেসক্রিপশন ও ওষুধ ডেলিভারি সার্ভিস পান</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===========================
         HOSPITALS SECTION WITH MODAL
    ============================ --}}
    <section class="bg-gradient-to-br from-red-50 to-orange-50 rounded-3xl p-12 shadow-xl">

        {{-- Emergency Notice --}}
        <div class="mt-8 bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
            <div class="flex items-center justify-center space-x-3 mb-3">
                <span class="text-2xl">🚨</span>
                <h3 class="text-xl font-bold text-red-800 {{ detectLanguageClass('জরুরি স্বাস্থ্য সেবা') }}">জরুরি স্বাস্থ্য সেবা</h3>
            </div>
            <p class="text-red-700 mb-4 {{ detectLanguageClass('যেকোনো জরুরি স্বাস্থ্য সমস্যায় দ্রুত নিকটস্থ হাসপাতালে যোগাযোগ করুন। জাতীয় জরুরি সেবার জন্য ৯৯৯ এ কল করুন।') }}">
                যেকোনো জরুরি স্বাস্থ্য সমস্যায় দ্রুত নিকটস্থ হাসপাতালে যোগাযোগ করুন। 
                জাতীয় জরুরি সেবার জন্য <strong class="inter">৯৯৯</strong> এ কল করুন।
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:999" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full font-semibold transition-colors duration-300 tiro">
                    🚑 জাতীয় জরুরি সেবা - ৯৯৯
                </a>
                <button onclick="showHospitalsModal()" class="border border-red-600 text-red-600 hover:bg-red-600 hover:text-white px-6 py-3 rounded-full font-semibold transition-colors duration-300 {{ detectLanguageClass('হাসপাতাল খুঁজুন') }}">
                    হাসপাতাল খুঁজুন
                </button>
            </div>
        </div>
    </section>

    {{-- ===========================
         CONTACT & SUPPORT
    ============================ --}}
    <section class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid md:grid-cols-2 gap-0">
            <div class="p-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 {{ detectLanguageClass('সহায়তা কেন্দ্র') }}">সহায়তা কেন্দ্র</h2>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <span class="text-2xl">📞</span>
                        <div>
                            <h3 class="font-semibold text-gray-800 {{ detectLanguageClass('২৪/৭ হেল্পলাইন') }}">২৪/৭ হেল্পলাইন</h3>
                            <p class="text-gray-600 inter">{{ setting('phone_number') }}</p>
                            <p class="text-gray-500 text-sm {{ detectLanguageClass('সকাল ৮টা থেকে রাত ১১টা পর্যন্ত') }}">সকাল ৮টা থেকে রাত ১১টা পর্যন্ত</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <span class="text-2xl">📧</span>
                        <div>
                            <h3 class="font-semibold text-gray-800 {{ detectLanguageClass('ইমেইল সাপোর্ট') }}">ইমেইল সাপোর্ট</h3>
                            <p class="text-gray-600 inter">{{ setting('email') }}</p>
                            <p class="text-gray-500 text-sm {{ detectLanguageClass('২৪ ঘন্টার মধ্যে রিপ্লাই') }}">২৪ ঘন্টার মধ্যে রিপ্লাই</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <span class="text-2xl">💬</span>
                        <div>
                            <h3 class="font-semibold text-gray-800 {{ detectLanguageClass('লাইভ চ্যাট') }}">লাইভ চ্যাট</h3>
                            <p class="text-gray-600 {{ detectLanguageClass('ওয়েবসাইট ও মোবাইল অ্যাপে উপলব্ধ') }}">ওয়েবসাইট ও মোবাইল অ্যাপে উপলব্ধ</p>
                            <p class="text-gray-500 text-sm {{ detectLanguageClass('সরাসরি বিশেষজ্ঞের সাথে কথা বলুন') }}">সরাসরি বিশেষজ্ঞের সাথে কথা বলুন</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-100 to-green-100 p-8 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-6xl mb-4">👨‍⚕️💙</div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 {{ detectLanguageClass('আমাদের সাথে যুক্ত হোন') }}">আমাদের সাথে যুক্ত হোন</h3>
                    <p class="text-gray-600 {{ detectLanguageClass('ডিজিটাল স্বাস্থ্য সেবার যুগে এগিয়ে যান') }}">ডিজিটাল স্বাস্থ্য সেবার যুগে এগিয়ে যান</p>
                    <button onclick="showRegistrationForm()" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full font-semibold transition-colors duration-300 {{ detectLanguageClass('এখনই রেজিস্ট্রেশন করুন') }}">
                        এখনই রেজিস্ট্রেশন করুন
                    </button>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- Hospitals Modal --}}
<div id="hospitalsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-3xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
        {{-- Modal Header --}}
        <div class="bg-red-500 px-6 py-4 text-white">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold {{ detectLanguageClass('সকল হাসপাতাল ও স্বাস্থ্য কেন্দ্র') }}">🏥 সকল হাসপাতাল ও স্বাস্থ্য কেন্দ্র</h3>
                <button onclick="closeHospitalsModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 max-h-[70vh] overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hospitals as $hospital)
                <div class="bg-gray-50 rounded-2xl p-6 border-l-4 
                    @if($hospital->type == 'government') border-green-500
                    @elseif($hospital->type == 'private') border-blue-500
                    @elseif($hospital->type == 'specialized') border-purple-500
                    @else border-gray-500 @endif">
                    
                    <div class="flex justify-between items-start mb-4">
                         <a href="{{ route('hospitals.view', $hospital->id) }}" class="hover:underline hover:text-blue-600 {{ detectLanguageClass($hospital->name) }}">
                            {{ $hospital->name }}
                        </a>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($hospital->type == 'government') bg-green-100 text-green-800
                            @elseif($hospital->type == 'private') bg-blue-100 text-blue-800
                            @elseif($hospital->type == 'specialized') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($hospital->type == 'government') সরকারি
                            @elseif($hospital->type == 'private') বেসরকারি
                            @elseif($hospital->type == 'specialized') বিশেষায়িত
                            @else ক্লিনিক @endif
                        </span>
                    </div>

                    @if($hospital->address)
                    <div class="flex items-start space-x-2 mb-3">
                        <span class="text-gray-500 mt-1">📍</span>
                        <p class="text-gray-600 text-sm {{ detectLanguageClass($hospital->address) }}">{{ $hospital->address }}</p>
                    </div>
                    @endif

                    <div class="space-y-2 mb-4">
                        @if($hospital->phone)
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-500">📞</span>
                            <a href="tel:{{ $hospital->phone }}" class="text-blue-600 hover:text-blue-800 inter text-sm">
                                {{ $hospital->phone }}
                            </a>
                        </div>
                        @endif

                        @if($hospital->emergency_contact)
                        <div class="flex items-center space-x-2">
                            <span class="text-red-500">🚨</span>
                            <a href="tel:{{ $hospital->emergency_contact }}" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                <span class="{{ detectLanguageClass('জরুরি') }}">জরুরি:</span> <span class='inter'>{{ $hospital->emergency_contact }}</span>
                            </a>
                        </div>
                        @endif
                    </div>

                    @if($hospital->services && count($hospital->services) > 0)
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700 text-sm mb-2 {{ detectLanguageClass('সেবাসমূহ') }}">সেবাসমূহ:</h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach($hospital->services as $service)
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs {{ detectLanguageClass($service) }}">
                                {{ $service }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="flex space-x-2 pt-4 border-t border-gray-200">
                        @if($hospital->phone)
                        <a href="tel:{{ $hospital->phone }}" 
                           class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded-lg text-sm font-semibold transition-colors duration-300 {{ detectLanguageClass('কল করুন') }}">
                           কল করুন
                        </a>
                        @endif
                        
                        @if($hospital->emergency_contact)
                        <a href="tel:{{ $hospital->emergency_contact }}" 
                           class="flex-1 bg-red-500 hover:bg-red-600 text-white text-center py-2 px-3 rounded-lg text-sm font-semibold transition-colors duration-300 {{ detectLanguageClass('জরুরি') }}">
                           জরুরি
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="bg-gray-100 px-6 py-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <button onclick="closeHospitalsModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-300 {{ detectLanguageClass('বন্ধ করুন') }}">
                    বন্ধ করুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showHospitalsModal() {
        document.getElementById('hospitalsModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeHospitalsModal() {
        document.getElementById('hospitalsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function showRegistrationForm() {
        document.getElementById('registerForm').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRegistrationModal() {
        document.getElementById('registrationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeHospitalsModal();
            closeRegistrationModal();
        }
    });

    // Close modals when clicking outside
    document.getElementById('hospitalsModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeHospitalsModal();
        }
    });

    document.getElementById('registrationModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRegistrationModal();
        }
    });

    // Registration form handling
    document.getElementById('registrationForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const registerButton = this.querySelector('button[type="submit"]');
        const errorDiv = document.getElementById('registerErrors');
        const errorMessage = document.getElementById('registerErrorMessage');
        
        // Show loading state
        registerButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>অ্যাকাউন্ট তৈরি হচ্ছে...';
        registerButton.disabled = true;
        errorDiv.classList.add('hidden');

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Registration successful
                closeRegistrationModal();
                window.location.href = data.redirect || '/student/dashboard';
            } else {
                // Registration failed
                errorMessage.textContent = data.message || 'রেজিস্ট্রেশন ব্যর্থ হয়েছে। আবার চেষ্টা করুন।';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            errorMessage.textContent = 'নেটওয়ার্ক ত্রুটি। আবার চেষ্টা করুন।';
            errorDiv.classList.remove('hidden');
        } finally {
            // Reset button state
            registerButton.innerHTML = '<i class="fas fa-user-plus mr-2"></i>অ্যাকাউন্ট তৈরি করুন';
            registerButton.disabled = false;
        }
    });
</script>
@endpush

@push('styles')
<style>
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }
</style>
@endpush