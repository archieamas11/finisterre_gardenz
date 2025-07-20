// Enable pusher logging - remove in production!
Pusher.logToConsole = false;

var pusher = new Pusher('2b048793924fa7a65cda', {
    cluster: 'ap3'
});

var channel = pusher.subscribe('admin-channel');
channel.bind('new-order', function (data) {
    // alert(JSON.stringify(data));

    // Play notification sound with user interaction handling
    var audio = new Audio('<?php echo WEBROOT; ?>assets/sounds/notification.mp3');

    // Try to play audio, handle autoplay restrictions
    var playPromise = audio.play();
    if (playPromise !== undefined) {
        playPromise.catch(function (error) {
            // Autoplay was prevented, store audio for later user interaction
            console.log('Audio autoplay prevented, will play on next user interaction');

            // Store the audio globally for later use
            window.pendingNotificationAudio = audio;

            // Add one-time click listener to play audio on next interaction
            document.addEventListener('click', function playDelayedAudio() {
                if (window.pendingNotificationAudio) {
                    window.pendingNotificationAudio.play().catch(function (e) {
                        console.log('Could not play delayed notification sound:', e);
                    });
                    window.pendingNotificationAudio = null;
                    document.removeEventListener('click', playDelayedAudio);
                }
            }, { once: true });
        });
    }

    showToast({
        title: "" + data.title + "",
        description: "" + data.message + "",
        type: "" + data.type + "",
    });

    // 🔴 Increment notification counter and show badge
    const counter = document.getElementById("notif-count");
    if (counter) {
        let current = parseInt(counter.innerText) || 0;
        counter.innerText = current + 1;

        // Show the badge if it was hidden
        counter.classList.remove('d-none');

        // Add pulse animation for new notifications
        counter.classList.add('notification-pulse');

        // Remove pulse animation after 3 seconds
        setTimeout(() => {
            counter.classList.remove('notification-pulse');
        }, 3000);
    }
});