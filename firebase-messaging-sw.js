importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyCJAgNY0w5pYHfrfQwbaC2H72eT4iRjJCY",
    authDomain: "gogeneric-3aae6.firebaseapp.com",
    projectId: "gogeneric-3aae6",
    storageBucket: "gogeneric-3aae6.firebasestorage.app",
    messagingSenderId: "1089940364207",
    appId: "1:1089940364207:web:b451a67708290573cf6d5a",
    measurementId: "G-KQN5CV496V"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    return self.registration.showNotification(payload.data.title, {
        body: payload.data.body ? payload.data.body : '',
        icon: payload.data.icon ? payload.data.icon : ''
    });
});