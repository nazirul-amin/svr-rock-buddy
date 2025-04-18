import party from 'party-js';

['😢', '😭', '🥲'].forEach((emoji, i) => {
    party.resolvableShapes[`sadEmoji${i}`] = `<span style="font-size:2rem">${emoji}</span>`;
});

export function launchConfettiParty(targetSelector = 'body') {
    const el = document.querySelector(targetSelector) || document.body;
    party.confetti(el, {
        count: 200,
        size: 1.5,
        spread: 60,
        speed: party.variation.range(400, 700),
    });
}

export function launchSadParty(targetSelector = 'body') {
    const el = document.querySelector(targetSelector) || document.body;
    party.confetti(el, {
        count: 80,
        size: party.variation.range(1.8, 2.2),
        spread: 50,
        speed: party.variation.range(300, 600),
        shapes: ['sadEmoji0', 'sadEmoji1', 'sadEmoji2'],
    });
}
