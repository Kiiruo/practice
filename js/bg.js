const attentionSection = document.querySelector('.attention');
const backgrounds = [
    '/img/bg_atention.png',
    '/img/bg_atention2.webp',
    '/img/bg_atention3.jpg'
];
let currentIndex = 0;

function changeBackground() {
    currentIndex = (currentIndex + 1) % backgrounds.length;
    attentionSection.style.backgroundImage = `url(${backgrounds[currentIndex]})`;
}

setInterval(changeBackground, 3000);