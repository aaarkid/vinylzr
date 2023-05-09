const leftBtn = document.querySelector('.nav-btn.left');
const rightBtn = document.querySelector('.nav-btn.right');
const vinylImage = document.querySelector('.vinyl-image');
const vinylTitle = document.querySelector('.vinyl-title');
const vinylText = document.querySelector('.vinyl-text');

const vinyls = [
    {
        image: 'images/vinyl1.png',
        title: 'Track Your Plays',
        text: 'Easily log each time you play a vinyl and keep a record of your listening history, allowing you to discover patterns and find your most played albums.'
    },
    {
        image: 'images/vinyl2.png',
        title: 'Organize Your Collection',
        text: 'Efficiently manage your vinyl collection by adding, editing, or removing albums, and easily search or sort by artist, title, or genre.'
    },
    {
        image: 'images/vinyl3.png',
        title: 'Discover New Music',
        text: 'Expand your musical horizons by exploring curated playlists, new releases, and personalized recommendations based on your listening habits.'
    },
    {
        image: 'images/vinyl4.png',
        title: 'Connect with Others',
        text: 'Join a vibrant community of fellow vinyl enthusiasts, share your collection, discuss your favorite records, and find friends with similar tastes.'
    },
];

let currentVinylIndex = 0;

function changeVinyl(index) {
    currentVinylIndex = index;
    vinylImage.src = vinyls[currentVinylIndex].image; // Change 'img' to 'image'
    vinylTitle.textContent = vinyls[currentVinylIndex].title;
    vinylText.textContent = vinyls[currentVinylIndex].text;
}

// Update this function to handle the fade in/fade out for text
function updateTextFade() {
    vinylTitle.style.opacity = 0;
    vinylText.style.opacity = 0;

    setTimeout(() => {
        vinylTitle.style.opacity = 1;
        vinylText.style.opacity = 1;
    }, 500);
}

function updateVinyl(direction) {
    let newIndex = currentVinylIndex + direction;
    if (newIndex < 0) {
        newIndex = vinyls.length - 1;
    } else if (newIndex >= vinyls.length) {
        newIndex = 0;
    }

    const wipe = createWipe(direction);
    const vinylImageContainer = document.querySelector('.vinyl-image-container');
    vinylImageContainer.appendChild(wipe);

    updateTextFade(); // Call the fade function for text

    // Animate wipe effect
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            wipe.style.width = '100%';
        });
    });

    setTimeout(() => {
        changeVinyl(newIndex);
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                wipe.style[direction > 0 ? 'right' : 'left'] = '0';
                wipe.style.width = '0';
            });
        });
    }, 500);

    // Remove wipe element after transition
    setTimeout(() => {
        vinylImageContainer.removeChild(wipe);
    }, 1000);
}

// Add this function to create the wipe effect
function createWipe(direction) {
    const wipe = document.createElement('div');
    wipe.classList.add('wipe');
    wipe.style.width = '0';
    wipe.style[direction > 0 ? 'left' : 'right'] = '0';
    return wipe;
}

leftBtn.addEventListener('click', () => updateVinyl(-1));
rightBtn.addEventListener('click', () => updateVinyl(1));
