importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');
let config = {
        apiKey: "AIzaSyDg1xBSwmHKV0usIKxTFL5a6fFTb4s3XVM",
        authDomain: "foodking-inilabs.firebaseapp.com",
        projectId: "foodking-inilabs",
        storageBucket: "foodking-inilabs.appspot.com",
        messagingSenderId: "843456771665",
        appId: "1:843456771665:web:fb1e3115e9e17ee1582a70",
        measurementId: "G-GSJPS921XW",
 };
firebase.initializeApp(config);
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/default/firebase-logo.png'
    };
    self.registration.showNotification(notificationTitle, notificationOptions);
});
