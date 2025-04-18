import { ref } from 'vue';

export function useAudio(src) {
    const audio = ref(null);

    if (!audio.value) {
        audio.value = new Audio(src);
    }

    function play() {
        if (audio.value) {
            audio.value.currentTime = 0;
            audio.value.play();
        }
    }

    function pause() {
        if (audio.value) {
            audio.value.pause();
        }
    }

    function stop() {
        if (audio.value) {
            audio.value.pause();
            audio.value.currentTime = 0;
        }
    }

    return { play, pause, stop, audio };
}
