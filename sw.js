var cacheName = 'argonPOS-IDB';
var appUrl = 'https://dev.localhost.com/pos/';

var dc = [
  `${appUrl}`,
  `${appUrl}dashboard`,
  `${appUrl}point-of-sale`,
  `${appUrl}sales`,
  `${appUrl}assets/img/brand/favicon.png`,
  `${appUrl}assets/vendor/nucleo/css/nucleo.css`,
  `${appUrl}assets/vendor/fortawesome/fontawesome-free/css/all.min.css`,
  `${appUrl}assets/vendor/sweetalert2/dist/sweetalert2.min.css`,
  `${appUrl}assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css`,
  `${appUrl}assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css`,
  `${appUrl}assets/vendor/jquery-steps/jquery.steps.css`,
  `${appUrl}assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css`,
  `${appUrl}assets/vendor/select2/dist/css/select2.min.css`,
  `${appUrl}assets/vendor/summernote/summernote-bs4.css`,
  `${appUrl}assets/css/argon.min9f1e.css`,
  `${appUrl}assets/css/custom.css`,
  `${appUrl}assets/vendor/jquery/dist/jquery.min.js`,
  `${appUrl}assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js`,
  `${appUrl}assets/vendor/js-cookie/js.cookie.js`,
  `${appUrl}assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js`,
  `${appUrl}assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js`,
  `${appUrl}assets/vendor/select2/dist/js/select2.min.js`,
  `${appUrl}assets/vendor/datatables.net/js/jquery.dataTables.min.js`,
  `${appUrl}assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js`,
  `${appUrl}assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js`,
  `${appUrl}assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js`,
  `${appUrl}assets/vendor/datatables.net-buttons/js/buttons.html5.min.js`,
  `${appUrl}assets/vendor/datatables.net-buttons/js/buttons.flash.min.js`,
  `${appUrl}assets/vendor/datatables.net-buttons/js/buttons.print.min.js`,
  `${appUrl}assets/vendor/datatables.net-select/js/dataTables.select.min.js`,
  `${appUrl}assets/vendor/apexcharts/apexcharts.min.js`,
  `${appUrl}assets/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js`,
  `${appUrl}assets/vendor/jvectormap/jquery-jvectormap-world-mill-en.js`,
  `${appUrl}assets/vendor/chart.js/dist/Chart.min.js`,
  `${appUrl}assets/vendor/chart.js/dist/Chart.extension.js`,
  `${appUrl}assets/vendor/moment/min/moment.min.js`,
  `${appUrl}assets/js/argon.min9f1e.js`,
  `${appUrl}assets/vendor/sweetalert2/dist/sweetalert2.min.js`,
  `${appUrl}assets/js/_js.v1.js`
];

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(cacheName).then((cache) => {
      return cache.addAll(dc);
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (evt) => {
  if(evt.request.method === 'GET') {
    evt.respondWith(
      fetch(evt.request).then((response) => {
        return caches.open(cacheName).then((cache) => {
          // cache.put(evt.request, response.clone());
          return response;
        })
      }).catch((err) => {
        return caches.match(evt.request).then((resp) => {
          if(resp === undefined) {
            return caches.match(`${appUrl}assets/offline.html`);
          }
          return resp;
        })
      })
    );
  };
});