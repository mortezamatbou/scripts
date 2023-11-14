const ws = new WebSocket('wss://stream.binance.com/stream?streams=btcusdt@depth');

ws.onopen = function (event) {
    console.log('connected');
};

ws.onmessage = function (event) {
    console.log(event.data);
};