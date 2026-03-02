<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Ronak Hacker Panel | Info Fetcher</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-glow: #00ff88;
            --secondary-glow: #0088ff;
            --bg-color: #050505;
            --text-color: #00ff88;
            --panel-bg: rgba(0, 20, 10, 0.85);
        }

        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            margin: 0;
            background: var(--bg-color);
            color: var(--text-color);
            font-family: 'Share Tech Mono', monospace;
            overflow-x: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* MATRIX CANVAS */
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: 0.5;
        }

        /* SCANLINE EFFECT */
        body::after {
            content: " ";
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            z-index: 100;
            background-size: 100% 2px, 3px 100%;
            pointer-events: none;
        }

        /* GLITCH ANIMATION FOR RONAK */
        .topbar {
            text-align: center;
            padding: 30px 10px;
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 5px;
            position: relative;
            z-index: 10;
        }

        .ronak-text {
            display: inline-block;
            color: #fff;
            text-shadow: 0 0 10px var(--primary-glow), 0 0 20px var(--primary-glow);
            animation: glitch 2s infinite, rgb-flow 5s infinite;
            transform-style: preserve-3d;
            perspective: 500px;
        }

        @keyframes rgb-flow {
            0% { color: #00ff88; text-shadow: 0 0 10px #00ff88; }
            33% { color: #0088ff; text-shadow: 0 0 10px #0088ff; }
            66% { color: #ff00ff; text-shadow: 0 0 10px #ff00ff; }
            100% { color: #00ff88; text-shadow: 0 0 10px #00ff88; }
        }

        @keyframes glitch {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px) skew(1deg); }
            40% { transform: translate(-2px, -2px) skew(-1deg); }
            60% { transform: translate(2px, 2px) skew(1deg); }
            80% { transform: translate(2px, -2px) skew(-1deg); }
            100% { transform: translate(0); }
        }

        /* SEARCH BOX */
        .search-container {
            width: 90%;
            max-width: 500px;
            margin: 0 auto 30px auto;
            position: relative;
            z-index: 10;
            display: flex;
            gap: 10px;
        }

        input {
            flex: 1;
            padding: 15px;
            background: rgba(0, 0, 0, 0.7);
            border: 1px solid var(--primary-glow);
            color: var(--primary-glow);
            font-family: 'Share Tech Mono', monospace;
            font-size: 16px;
            outline: none;
            box-shadow: inset 0 0 10px rgba(0, 255, 136, 0.2);
            border-radius: 4px;
        }

        button {
            padding: 15px 25px;
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid var(--primary-glow);
            color: var(--primary-glow);
            font-family: 'Share Tech Mono', monospace;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 4px;
        }

        button:hover {
            background: var(--primary-glow);
            color: #000;
            box-shadow: 0 0 20px var(--primary-glow);
        }

        /* RESULT PANEL - GLASSMORPHISM */
        .result-panel {
            flex: 1;
            width: 95%;
            max-width: 900px;
            margin: 0 auto 100px auto;
            background: var(--panel-bg);
            border: 1px solid var(--primary-glow);
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.15), inset 0 0 15px rgba(0, 255, 136, 0.1);
            padding: 20px;
            border-radius: 10px;
            overflow-y: auto;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(10px);
            transform: perspective(1000px) rotateX(2deg);
            transition: all 0.5s ease;
        }

        .result-panel:hover {
            transform: perspective(1000px) rotateX(0deg) scale(1.01);
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.3);
        }

        /* ERROR BLINKING EFFECT */
        .error-panel {
            border-color: #ff3333 !important;
            box-shadow: 0 0 30px rgba(255, 51, 51, 0.3) !important;
            animation: error-blink 0.8s infinite alternate !important;
        }

        @keyframes error-blink {
            0% { box-shadow: 0 0 10px rgba(255, 51, 51, 0.2); border-color: #ff3333; }
            100% { box-shadow: 0 0 40px rgba(255, 51, 51, 0.7); border-color: #ff0000; }
        }

        #result {
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        .error-text {
            color: #ff3333;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 51, 51, 0.5);
        }

        /* 3D FLOATING BUTTON */
        .copybtn {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 110;
            background: #000;
            border: 2px solid var(--primary-glow);
            padding: 15px 40px;
            box-shadow: 0 10px 20px rgba(0, 255, 136, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(-50%, 0); }
            50% { transform: translate(-50%, -10px); }
        }

        /* MOBILE ADJUSTMENTS */
        @media (max-width: 600px) {
            .topbar { font-size: 20px; }
            .search-container { flex-direction: column; }
            input, button { width: 100%; }
            .result-panel { height: calc(100vh - 300px); }
        }
    </style>
</head>
<body>

<canvas id="matrix"></canvas>

<div class="topbar">
    CREATED BY <span class="ronak-text">RONAK</span> 💻
</div>

<div class="search-container">
    <input type="tel" id="mobile" placeholder="ENTER TARGET NUMBER..." autocomplete="off">
    <button onclick="fetchData()">SEARCH</button>
</div>

<div class="result-panel">
    <pre id="result">
[SYSTEM READY]
WAITING FOR INPUT...
    </pre>
</div>

<button class="copybtn" id="copyBtn" onclick="copyData()">COPY SECURE DATA</button>

<script>
    // MATRIX BACKGROUND ENHANCED
    const canvas = document.getElementById("matrix");
    const ctx = canvas.getContext("2d");

    function resizeCanvas() {
        canvas.height = window.innerHeight;
        canvas.width = window.innerWidth;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    const letters = "0101010101010111001";
    const fontSize = 16;
    let columns = canvas.width / fontSize;
    const drops = [];

    for (let x = 0; x < columns; x++) drops[x] = 1;

    function draw() {
        ctx.fillStyle = "rgba(0, 0, 0, 0.05)";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = "#00ff88";
        ctx.font = fontSize + "px 'Share Tech Mono'";

        for (let i = 0; i < drops.length; i++) {
            const text = letters.charAt(Math.floor(Math.random() * letters.length));
            ctx.fillText(text, i * fontSize, drops[i] * fontSize);

            if (drops[i] * fontSize > canvas.height && Math.random() > 0.975)
                drops[i] = 0;

            drops[i]++;
        }
    }
    setInterval(draw, 35);

    // FETCH DATA
    function fetchData() {
        let mobile = document.getElementById("mobile").value;
        const panel = document.querySelector('.result-panel');
        const result = document.getElementById("result");
        
        if (mobile === "") {
            result.innerHTML = "<span class='error-text'>[ERROR] ENTER VALID NUMBER</span>";
            return;
        }

        // Reset UI
        panel.classList.remove('error-panel');
        result.textContent = ">>> INITIALIZING SECURE BYPASS...\n>>> CONNECTING TO PROPORTAL SERVER...\n>>> ACCESSING DATABASE MODULE...\n\n";
        document.getElementById("copyBtn").style.display = "none";

        fetch("api.php?mobile=" + mobile)
            .then(res => res.json())
            .then(data => {
                // Check structures for both new and old API versions
                let resultsList = [];
                
                if (data.data) {
                    if (Array.isArray(data.data)) {
                        resultsList = data.data;
                    } else if (typeof data.data === 'object') {
                        resultsList = data.data.results || data.data.data || [];
                    }
                } else if (data.result) {
                    if (Array.isArray(data.result)) {
                        resultsList = data.result;
                    } else if (typeof data.result === 'object') {
                        resultsList = data.result.results || data.result.data || [];
                    }
                } else if (data.results && Array.isArray(data.results)) {
                    resultsList = data.results;
                }
                
                const isNotFound = resultsList.length === 0;

                if (isNotFound) {
                    panel.classList.add('error-panel');
                    typeWriter("DATA NOT FOUND\n\n[SYSTEM STATUS: TARGET SEARCH FAILED]\n[ALERT] NO RECORDS CORRESPONDING TO THIS NUMBER WERE LOCATED IN THE SECURE DATABASE.", true);
                } else {
                    let displayStr = ">>> TARGET DATA DECRYPTED SUCCESSFULLY <<<\n\n";
                    
                    resultsList.forEach((item, index) => {
                        displayStr += `[RESULT #${index + 1}]\n`;
                        displayStr += `NAME          : ${item.name || 'N/A'}\n`;
                        displayStr += `FATHER NAME   : ${item.father_name || item.fname || 'N/A'}\n`;
                        displayStr += `MOBILE        : ${item.mobile || 'N/A'}\n`;
                        displayStr += `ALT MOBILE    : ${item.alt_mobile || item.alt || 'N/A'}\n`;
                        displayStr += `ADDRESS       : ${item.address || 'N/A'}\n`;
                        displayStr += `CIRCLE        : ${item.circle || 'N/A'}\n`;
                        displayStr += `AADHAAR NO.   : ${item.aadhaar_number || item.id || 'N/A'}\n`;
                        displayStr += `EMAIL         : ${item.email || 'N/A'}\n`;
                        displayStr += `------------------------------------------\n\n`;
                    });
                    
                    displayStr += `[SYSTEM STATUS: SEARCH COMPLETE]\n[BYPASS CREATED BY RONAK]`;

                    typeWriter(displayStr, false);
                }
            })
            .catch(err => {
                panel.classList.add('error-panel');
                typeWriter("[CONNECTION ERROR] ERROR PARSING DATA OR SERVICE IS DOWN.", true);
            });
    }

    // TYPING EFFECT
    function typeWriter(text, isError) {
        let i = 0;
        let speed = 2; 
        let result = document.getElementById("result");
        result.textContent = "";
        
        if (isError) {
            result.classList.add('error-text');
        } else {
            result.classList.remove('error-text');
        }

        function typing() {
            if (i < text.length) {
                result.textContent += text.charAt(i);
                const panel = document.querySelector('.result-panel');
                panel.scrollTop = panel.scrollHeight;
                i++;
                setTimeout(typing, speed);
            } else {
                if (!isError) {
                    document.getElementById("copyBtn").style.display = "block";
                }
            }
        }
        typing();
    }

    // COPY
    function copyData() {
        let text = document.getElementById("result").innerText;
        navigator.clipboard.writeText(text);
        
        const btn = document.getElementById("copyBtn");
        btn.textContent = "COPIED TO CLIPBOARD!";
        setTimeout(() => {
            btn.textContent = "COPY SECURE DATA";
        }, 2000);
    }
</script>

</body>
</html>