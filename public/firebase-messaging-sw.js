// importScripts('https://cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js');
// importScripts('https://www.gstatic.com/firebasejs/9.13.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

     const firebaseConfig = {
        apiKey: "AIzaSyDcwVNZGRAFyRBb1woVv_2eygurTcfe3IM",
        authDomain: "ride-with-passenger-go.firebaseapp.com",
        projectId: "ride-with-passenger-go",
        storageBucket: "ride-with-passenger-go.appspot.com",
        messagingSenderId: "892581690989",
        appId: "1:892581690989:web:eacdd7a3ff8c8fa76d8964"
      };
    
      // Initialize Firebase
      const app = firebase.initializeApp(firebaseConfig);
      const messaging = firebase.messaging();
      var data=0;
      
      messaging.setBackgroundMessageHandler(function(payload) {
        data=1;
        console.log(
            "[firebase-messaging-sw.js] Received background message ",
            payload,
        );
        /* Customize notification here */
        const notificationTitle = payload.data.title;
        const notificationOptions = {
            body: "Check details",
            icon: "/itwonders-web-logo.png",
            
        };
        //showCustomNotification(payload.data);
        
        return self.registration.showNotification(notificationTitle, notificationOptions);
    });
    
    
    
    