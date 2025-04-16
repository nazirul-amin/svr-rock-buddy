<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#ff5a63] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#ff5a63] hover:bg-[#ff5a63] hover:text-white dark:border-[#ff5a63] dark:text-[#EDEDEC] dark:hover:border-[#ff5a63] dark:hover:bg-[#ff5a63]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-[#ff5a63] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#ff5a63] hover:bg-[#ff5a63] hover:text-white dark:border-[#ff5a63] dark:text-[#EDEDEC] dark:hover:border-[#ff5a63] dark:hover:bg-[#ff5a63]"
                    >
                        Login
                    </Link>
                </template>
            </nav>
        </header>
        <div class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:rounded-br-none lg:rounded-tl-lg lg:p-20"
                >
                    <h1 class="mb-1 font-medium">Welcome to Rock Buddy</h1>
                    <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                        Your monthly pet rock photo competition platform. <br />Show off your creativity and win prizes!
                    </p>
                    <ul class="mb-4 flex flex-col lg:mb-6">
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:bottom-0 before:left-[0.4rem] before:top-1/2 before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span class="relative bg-white py-1 dark:bg-[#161615]">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#ff5a63] dark:bg-[#ff5a63]" />
                                </span>
                            </span>
                            <span>
                                Submit photos of your pet rock matching monthly themes
                            </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:bottom-1/2 before:left-[0.4rem] before:top-0 before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span class="relative bg-white py-1 dark:bg-[#161615]">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#fe804d] dark:bg-[#fe804d]" />
                                </span>
                            </span>
                            <span>
                                Track your competition score and ranking
                            </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:bottom-1/2 before:left-[0.4rem] before:top-0 before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A]"
                        >
                            <span class="relative bg-white py-1 dark:bg-[#161615]">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#FDFDFC] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] dark:border-[#3E3E3A] dark:bg-[#161615]"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#ff5a63] dark:bg-[#ff5a63]" />
                                </span>
                            </span>
                            <span>
                                Receive monthly newsletters with winners and new themes
                            </span>
                        </li>
                    </ul>
                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <a
                                href="#"
                                class="inline-block rounded-sm border border-[#ff5a63] bg-[#ff5a63] px-5 py-1.5 text-sm leading-normal text-white hover:border-[#ff5a63] hover:bg-[#fc3f49] dark:border-[#ff5a63] dark:bg-[#ff5a63] dark:text-white dark:hover:border-[#fc3f49] dark:hover:bg-[#fc3f49]"
                            >
                                Join Competition
                            </a>
                        </li>
                    </ul>
                </div>
                <div
                    class="relative -mb-px aspect-335/376 w-full shrink-0 overflow-hidden rounded-t-lg bg-[#fff2f2] dark:bg-[#1D0002] lg:-ml-px lg:mb-0 lg:aspect-auto lg:w-[438px] lg:rounded-r-lg lg:rounded-t-none"
                >
                    <div class="flex h-full flex-col items-center justify-center p-8 lg:p-12">
                        <svg class="w-24 h-24 lg:w-32 lg:h-32 text-[#ff5a63]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8c0-3.3-2.7-6-6-6S6 4.7 6 8c0 2.6 1.7 4.8 4 5.7V16H8v2h2v2h4v-2h2v-2h-2v-2.3c2.3-.9 4-3.1 4-5.7z"/>
                            <path d="M12 19v3"/>
                            <path d="M10 22h4"/>
                        </svg>
                        <h1 class="mt-6 text-4xl font-bold text-[#ff5a63]">Rock Buddy</h1>
                        <p class="mt-2 text-xl font-medium text-[#fe804d]">Pet Rock Photo Competition</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
