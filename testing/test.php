<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f5f5f5;
    }

    .notification-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      max-width: 600px; /* Increased from 400px */
    }

    .notification {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      margin-bottom: 10px;
      transform: translateX(450px);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      position: relative;
      overflow: hidden;
    }

    .notification.show {
      transform: translateX(0);
    }

    .notification::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
      background-size: 200% 100%;
      animation: shimmer 2s ease-in-out infinite;
    }

    @keyframes shimmer {
      0% { background-position: -200% 0; }
      100% { background-position: 200% 0; }
    }

    .notification-header {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .notification-icon {
      width: 24px;
      height: 24px;
      margin-right: 12px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    .notification-title {
      font-weight: 600;
      font-size: 16px;
      margin: 0;
      flex: 1;
    }

    .notification-close {
      background: none;
      border: none;
      color: white;
      font-size: 20px;
      cursor: pointer;
      padding: 0;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: background-color 0.2s;
    }

    .notification-close:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .notification-content {
      font-size: 14px;
      line-height: 1.5;
      opacity: 0.9;
    }

    .notification-time {
      font-size: 12px;
      opacity: 0.7;
      margin-top: 8px;
    }

    .main-content {
      max-width: 800px;
      margin: 0 auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #333;
      margin-bottom: 20px;
    }

    p {
      color: #666;
      line-height: 1.6;
    }

    code {
      background: #f8f9fa;
      padding: 2px 6px;
      border-radius: 4px;
      font-family: 'Courier New', monospace;
      color: #e83e8c;
    }

    .test-button {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 20px;
      transition: transform 0.2s;
    }

    .test-button:hover {
      transform: translateY(-2px);
    }
  </style>
  <script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('2b048793924fa7a65cda', {
      cluster: 'ap3'
    });

    var channel = pusher.subscribe('my-channel');
    
    // Enhanced notification function
    function showNotification(data) {
      const container = document.getElementById('notification-container');
      
      const notification = document.createElement('div');
      notification.className = 'notification';
      
      const currentTime = new Date().toLocaleTimeString();
      
      notification.innerHTML = `
        <div class="notification-header">
          <div class="notification-icon"><i class="bi bi-bell-fill"></i></div>
          <h3 class="notification-title">New Event Received</h3>
          <button class="notification-close" onclick="removeNotification(this)">&times;</button>
        </div>
        <div class="notification-content">
          ${data.message}
        </div>
        <div class="notification-time">
          Received at ${currentTime}
        </div>
      `;
      
      container.appendChild(notification);
      
      // Trigger animation
      setTimeout(() => {
        notification.classList.add('show');
      }, 100);
      
      // Auto remove after 10 seconds
      setTimeout(() => {
        removeNotification(notification.querySelector('.notification-close'));
      }, 10000);
    }

    function removeNotification(button) {
      const notification = button.closest('.notification');
      notification.classList.remove('show');
      setTimeout(() => {
        notification.remove();
      }, 400);
    }

    // Test function
    function testNotification() {
      showNotification({
        message: "This is a test notification!",
        timestamp: new Date().toISOString(),
        type: "test"
      });
    }

    channel.bind('my-event', function(data) {
      showNotification(data);
    });
  </script>
</head>
<body>
  <!-- Notification Container -->
  <div id="notification-container" class="notification-container"></div>

  <div class="main-content">
    <h1>Enhanced Pusher Notifications</h1>
    <p>
      Try publishing an event to channel <code>my-channel</code>
      with event name <code>my-event</code>. You'll see a beautiful styled notification instead of a basic alert!
    </p>
    <button class="test-button" onclick="testNotification()">Test Notification</button>
  </div>
</body>