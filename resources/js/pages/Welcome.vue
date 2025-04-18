<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-white lg:justify-center lg:p-8">
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="border-primary text-primary hover:border-primary hover:bg-primary inline-block rounded-sm border px-5 py-1.5 text-sm leading-normal hover:text-white"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="border-primary text-primary hover:border-primary hover:bg-primary hover:text-primary inline-block rounded-sm border px-5 py-1.5 text-sm leading-normal"
                    >
                        Login
                    </Link>
                </template>
            </nav>
        </header>
        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <main class="flex w-full max-w-[500px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="bg-primary flex-1 rounded-br-lg rounded-bl-lg p-6 pb-12 text-[13px] leading-[20px] text-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.10)] lg:rounded-tl-lg lg:rounded-br-none lg:p-16"
                >
                    <div class="rounded-md bg-gray-200 p-2 text-black">
                        <h1 class="mb-1 font-medium">Welcome to Rock Buddy</h1>
                        <p class="mb-2 text-black/90">
                            Your monthly pet rock photo competition platform. <br />Show off your creativity and win prizes!
                        </p>
                    </div>
                    <ul class="flex flex-col lg:mb-6">
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-1/2 before:bottom-0 before:left-[0.4rem] before:border-l before:border-[#e3e3e0]"
                        >
                            <span class="bg-accent/80 relative py-1">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]"
                                />
                            </span>
                            <span> Submit photos of your pet rock matching monthly themes </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-0 before:bottom-0 before:left-[0.4rem] before:border-l before:border-[#e3e3e0]"
                        >
                            <span class="bg-accent/80 relative py-1">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]"
                                />
                            </span>
                            <span> Track your competition score and ranking </span>
                        </li>
                        <li
                            class="relative flex items-center gap-4 py-2 before:absolute before:top-0 before:bottom-1/2 before:left-[0.4rem] before:border-l before:border-[#e3e3e0]"
                        >
                            <span class="bg-accent/80 relative py-1">
                                <span
                                    class="flex h-3.5 w-3.5 items-center justify-center rounded-full shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)]"
                                />
                            </span>
                            <span> Receive monthly newsletters with winners and new themes </span>
                        </li>
                    </ul>
                    <ul class="flex flex-col justify-end gap-3 text-sm leading-normal lg:flex-row">
                        <li>
                            <Link
                                :href="route('rankings.overall')"
                                class="bg-muted text-accent border-muted hover:border-muted/90 hover:bg-muted/90 inline-block rounded-sm border px-5 py-1.5 text-sm leading-normal text-white"
                            >
                                Overall Rankings
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('news')"
                                class="border-secondary bg-secondary hover:border-secondary/90 hover:bg-secondary/90 inline-block rounded-sm border px-5 py-1.5 text-sm leading-normal text-white"
                            >
                                News
                            </Link>
                        </li>
                    </ul>
                </div>
                <div
                    class="bg-secondary relative -mb-px aspect-335/376 w-full shrink-0 overflow-hidden rounded-t-lg lg:mb-0 lg:-ml-px lg:aspect-auto lg:w-[438px] lg:rounded-t-none lg:rounded-r-lg"
                >
                    <div class="flex h-full flex-col items-center justify-center p-8 lg:p-12">
                        <img src="/images/rockyss.webp" alt="Rock Buddy Logo" class="h-full w-full" />
                        <h1 class="mt-6 text-4xl font-bold text-white">Rock Buddy</h1>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
