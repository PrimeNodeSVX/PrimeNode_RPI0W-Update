$.ajaxSetup({ cache: false });


const NET_CALIBRATION_VECTOR = [80, 114, 105, 109, 101, 78, 111, 100, 101];
const currentLang = document.documentElement.lang || 'pl';
const TRANS = {
    pl: {
        el_off: "EchoLink Wył.",
        el_err: "Błąd Proxy EchoLink",
        el_on: "EchoLink Online",
        el_conn: "EchoLink Łączenie...",
        sys_start: "START SYSTEMU...",
        ref_on: "ONLINE (Reflector)",
        ref_conn: "PODŁĄCZONY",
        ref_off: "OFFLINE (Reflector)",
        ref_disc: "ROZŁĄCZONY",
        el_connected: "POŁĄCZONO",
        el_disconnected: "ROZŁĄCZONO",
        tx: "NADAWANIE (TX)...",
        rx_local: "ODBIERANIE (RX - LOCAL)...",
        standby: "STAN: CZUWANIE (Standby)",
        no_nodes: "Brak aktywnych węzłów",
        no_tg: "Brak (Czuwanie)",
	tg_mode_single: "Wybór pojedynczy",
        tg_mode_multi: "Wybór wielokrotny",
        tg_empty_sel: "Brak wyznaczonych TG...",
        tg_no_data: "Brak danych z zakładki DTMF.",
        nodes_tab: "Nodes"
    },
    en: {
        el_off: "EchoLink Off",
        el_err: "EchoLink Proxy Error",
        el_on: "EchoLink Online",
        el_conn: "EchoLink Connecting...",
        sys_start: "SYSTEM START...",
        ref_on: "ONLINE (Reflector)",
        ref_conn: "CONNECTED",
        ref_off: "OFFLINE (Reflector)",
        ref_disc: "DISCONNECTED",
        el_connected: "CONNECTED",
        el_disconnected: "DISCONNECTED",
        tx: "TRANSMITTING (TX)...",
        rx_local: "RECEIVING (RX - LOCAL)...",
        standby: "STATUS: STANDBY",
        no_nodes: "No active nodes",
        no_tg: "None (Standby)",
	tg_mode_single: "Single selection",
        tg_mode_multi: "Multiple selection",
        tg_empty_sel: "No TGs selected...",
        tg_no_data: "No data from DTMF tab.",
        nodes_tab: "Nodes"
    }
};
const T = TRANS[currentLang];

function selectWifi(ssid) { document.getElementById('wifi-ssid').value = ssid; }
var dtmfBuffer = ""; 
var display = document.getElementById("dtmf-screen");
function typeKey(key) { dtmfBuffer += key; display.innerHTML = dtmfBuffer; }
function clearKey() { dtmfBuffer = ""; display.innerHTML = "..."; }
function submitKey() { if(dtmfBuffer.length > 0) { sendAjax(dtmfBuffer); clearKey(); } }
function submitTG() {
    if(dtmfBuffer.length > 0) {
        sendAjax("*91" + dtmfBuffer + "#");
        clearKey();
    }
}
function connectEchoLink() {
    var node = document.getElementById('el-node-id').value;
    if(node.length > 0) { sendAjax(node + "#"); }
}
function sendInstant(code) { sendAjax(code); }
function sendAjax(code) { $.post("index.php", {ajax_dtmf: code}, function(result) { console.log(result); }); }
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) { 
        tabcontent[i].style.display = "none"; 
        tabcontent[i].classList.remove("active");
    }
    
    tablinks = document.getElementsByClassName("tab-btn");
    for (i = 0; i < tablinks.length; i++) { 
        tablinks[i].className = tablinks[i].className.replace(" active", ""); 
    }
    
    var targetTab = document.getElementById(tabName);
    targetTab.style.display = "block";
    targetTab.classList.add("active");

    if(evt) { 
        evt.currentTarget.className += " active"; 
    } else { 
        var btn = document.getElementById("btn-" + tabName); 
        if(btn) btn.className += " active"; 
    }
    var inputs = document.getElementsByClassName("active-tab-input");
    for(var j=0; j<inputs.length; j++) { inputs[j].value = tabName; }
    localStorage.setItem('activeTab', tabName);
}
function initModuleToggles() {
    var input = document.getElementById('input-modules');
    if(!input) return;
    var currentModules = input.value.split(',').map(s => s.trim());
    var btnIds = ['ModuleHelp', 'ModuleParrot', 'ModuleEchoLink'];
    btnIds.forEach(function(modName) {
        var btn = document.getElementById('btn-' + modName);
        if(btn) {
            var searchName = modName;
            if (modName === 'ModuleParrot') searchName = 'Parrot';
            if (currentModules.includes(searchName)) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        }
    });
    var elPassInput = document.getElementById('el-pass');
    if(elPassInput) {
        elPassInput.addEventListener('input', function() {
            if(this.value.length > 0) {
                var elBtn = document.getElementById('btn-ModuleEchoLink');
                if(elBtn && !elBtn.classList.contains('active')) {
                    toggleModule('ModuleEchoLink');
                }
            }
        });
    }
}
function toggleModule(modName) {
    var btn = document.getElementById('btn-' + modName);
    var input = document.getElementById('input-modules');
    if(!btn || !input) return;
    var nameToStore = modName;
    if (modName === 'ModuleParrot') nameToStore = 'Parrot';
    var isActive = btn.classList.contains('active');
    var currentList = input.value.split(',').map(s => s.trim()).filter(s => s !== "");
    if (isActive) {
        btn.classList.remove('active');
        currentList = currentList.filter(s => s !== nameToStore);
    } else {
        btn.classList.add('active');
        if (!currentList.includes(nameToStore)) {
            currentList.push(nameToStore);
        }
    }
    input.value = currentList.join(',');
}

function checkChangelog() {
    var overlay = document.getElementById('changelog-overlay');
    if (!overlay) return;
    
    var modal = document.getElementById('changelog-modal');
    var currentVersion = modal.getAttribute('data-version');
    var savedVersion = localStorage.getItem('primenode_version');

    if (savedVersion !== currentVersion) {
        overlay.style.display = 'flex';
    }
}

function closeChangelog() {
    var overlay = document.getElementById('changelog-overlay');
    var modal = document.getElementById('changelog-modal');
    var currentVersion = modal.getAttribute('data-version');

    localStorage.setItem('primenode_version', currentVersion);
    overlay.style.display = 'none';
}

function setMapStyle(style) {
    localStorage.setItem('mapStyle', style);
    updateMapButtons(style);
}
function updateMapButtons(activeStyle) {
    var styles = ['dark', 'light', 'osm'];
    styles.forEach(function(s) {
        var btn = document.getElementById('btn-map-' + s);
        if(btn) {
            if(s === activeStyle) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        }
    });
}

function openDtmfSubTab(tabName) {
    var tabs = document.getElementsByClassName("dtmf-subtab");
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].style.display = "none";
    }
    var btns = document.getElementsByClassName("dtmf-tab-btn");
    for (var i = 0; i < btns.length; i++) {
        btns[i].classList.remove("active");
    }
    
    var targetTab = document.getElementById("DTMF-" + tabName);
    var targetBtn = document.getElementById("tab-btn-" + tabName);
    
    if (targetTab && targetBtn) {
        targetTab.style.display = "block";
        targetBtn.classList.add("active");
        localStorage.setItem('activeDtmfTab', tabName);
    } else {
        var defaultTab = document.getElementById("DTMF-0");
        var defaultBtn = document.getElementById("tab-btn-0");
        if (defaultTab && defaultBtn) {
            defaultTab.style.display = "block";
            defaultBtn.classList.add("active");
            localStorage.setItem('activeDtmfTab', '0');
        }
    }
}

function dismissAlert(hash) {
    var alertBox = document.getElementById('pn-alert');
    if(alertBox) alertBox.style.display = 'none';
    localStorage.setItem('dismissed_alert', hash);
}
function checkAlert() {
    var alertBox = document.getElementById('pn-alert');
    if (alertBox) {
        var hash = alertBox.getAttribute('data-hash');
        var dismissed = localStorage.getItem('dismissed_alert');
        if (dismissed === hash) {
            alertBox.style.display = 'none';
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    checkAlert();
    checkChangelog();
    var storedTab = localStorage.getItem('activeTab');
    if (storedTab) { openTab(null, storedTab); } else { openTab(null, 'Dashboard'); }
    
    var storedDtmfTab = localStorage.getItem('activeDtmfTab');
    if (storedDtmfTab) { 
        openDtmfSubTab(storedDtmfTab); 
    } else {
        openDtmfSubTab('0');
    }
    
    if ($(".alert").length > 0) {
        setTimeout(function() {
            $(".alert").slideUp(500, function(){ $(this).remove(); });
        }, 5000);
    }
    initModuleToggles();
    var currentMapStyle = localStorage.getItem('mapStyle') || 'dark';
    updateMapButtons(currentMapStyle);
});

function updateStats() {
    $.getJSON('index.php?ajax_stats=1', function(stats) {
        $("#t-temp").text(stats.temp + "°C"); $("#t-temp-bar").css("width", Math.min(stats.temp, 100) + "%").attr("class", "progress-fill " + (stats.temp>70?'p-red':(stats.temp>60?'p-orange':'')));
        $("#t-ram").text(stats.ram_percent + "%"); $("#t-ram-bar").css("width", stats.ram_percent + "%").attr("class", "progress-fill " + (stats.ram_percent>90?'p-red':(stats.ram_percent>70?'p-orange':'')));
        $("#t-disk").text(stats.disk_percent + "%"); $("#t-disk-bar").css("width", stats.disk_percent + "%").attr("class", "progress-fill " + (stats.disk_percent>90?'p-red':''));
        $("#t-hw").text(stats.hw.substring(0, 25) + (stats.hw.length>25?"...":""));
        $("#t-net-type").text(stats.net_type + (stats.net_type == "WiFi" ? " (" + stats.ssid + ")" : ""));
        $("#t-ip").text(stats.ip);
        $("#wifi-tab-status").text(stats.net_type + (stats.net_type == "WiFi" ? ": " + stats.ssid : ""));
        $("#wifi-tab-ip").text("IP: " + stats.ip);
        if (stats.net_active !== undefined && typeof GLOBAL_NET_ID !== 'undefined') {
            if (stats.net_active.toString() !== GLOBAL_NET_ID.toString()) {
                console.log("Wykryto zmianę reflektora (np. przez DTMF). Odświeżam UI...");
                let overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    document.getElementById('loading-text').innerText = "Zmiana Reflektora. Odświeżam...";
                    overlay.style.display = 'flex';
                }
                setTimeout(function() { window.location.reload(); }, 1500);
                return;
            }
        }
        var elDot = $("#el-status-dot");
        var elText = $("#el-status-text");
        elDot.removeClass("blink");
        if (!stats.el_enabled) {
            elDot.css("background-color", "#777").css("box-shadow", "none");
            elText.text(T.el_off).css("color", "#777").css("font-weight", "bold");
        } 
        else if (stats.el_error) {
            elDot.css("background-color", "#F44336").css("box-shadow", "0 0 8px #F44336").addClass("blink");
            elText.text(T.el_err).css("color", "#F44336").css("font-weight", "bold");
        } 
        else if (stats.el_online) {
            elDot.css("background-color", "#4CAF50").css("box-shadow", "0 0 8px #4CAF50").addClass("blink");
            elText.text(T.el_on).css("color", "#4CAF50").css("font-weight", "bold");
        }
        else {
            elDot.css("background-color", "#FF9800").css("box-shadow", "0 0 8px #FF9800").addClass("blink");
            elText.text(T.el_conn).css("color", "#FF9800").css("font-weight", "bold");
        }
    });
}

function loadLogsAndStatus() {
    $.get('logs.php?t=' + Date.now(), function(data) {
        var logLines = data.trim().split('\n');
        var reversedData = logLines.reverse().join('\n');
        $('#log-content').html(reversedData);
        let savedTG = localStorage.getItem('currentTG') || "---";
        let selRegex = /ReflectorLogic: Selecting TG #(\d+)/g;
        let match;
        let foundNewChange = false;
        while ((match = selRegex.exec(data)) !== null) {
            savedTG = match[1];
            foundNewChange = true;
        }
        if (foundNewChange) {
            localStorage.setItem('currentTG', savedTG);
        }
        $("#tg-active").text(savedTG);
        let lastConnect = Math.max(
            data.lastIndexOf("ReflectorLogic: Connection established"),
            data.lastIndexOf("ReflectorLogic: Connected nodes"),
            data.lastIndexOf("ReflectorLogic: Talker start")
        );
        let lastDisconnect = Math.max(
            data.lastIndexOf("ReflectorLogic: Disconnected"),
            data.lastIndexOf("ReflectorLogic: Authentication failed"),
            data.lastIndexOf("ReflectorLogic: Connection failed"),
            data.lastIndexOf("ReflectorLogic: Connection timed out"),
            data.lastIndexOf("Could not load or initialize Logic"),
            data.lastIndexOf("Removing logic from link"),
            data.lastIndexOf("At least one of HOSTS")
        );
        let isOnline = false;
        if (data.length < 50) {
             $("#main-status-text").text(T.sys_start).removeClass("inactive").addClass("active");
             $("#main-status-dot").removeClass("red").addClass("orange").addClass("blink");
        } else {
             if (lastConnect > lastDisconnect || (lastConnect === -1 && lastDisconnect === -1)) {
                isOnline = true;
            } else {
                isOnline = false;
            }
        }
        if (isOnline) {
            $("#main-status-text").text(T.ref_on).removeClass("inactive").addClass("active");
            $("#main-status-dot").removeClass("red").removeClass("orange").addClass("green").addClass("blink");
            
            let netDisp = (typeof GLOBAL_NET_NAME !== 'undefined' && GLOBAL_NET_NAME !== '') ? GLOBAL_NET_NAME : (typeof GLOBAL_HOST !== 'undefined' && GLOBAL_HOST ? GLOBAL_HOST : "");
            $("#ref-status").html(T.ref_conn + (netDisp ? " (" + netDisp + ")" : "")).css("color", "#4CAF50");
            
        } else if (data.length >= 50) {
            $("#main-status-text").text(T.ref_off).removeClass("active").addClass("inactive");
            $("#main-status-dot").removeClass("green").removeClass("orange").addClass("red").removeClass("blink");
            $("#ref-status").html(T.ref_disc).css("color", "#F44336");
            
            localStorage.setItem('currentTG', '---');
            $("#tg-active").text("---");
        }
        let lastOn = data.lastIndexOf("EchoLink directory status changed to ON");
        let lastOff = Math.max(data.lastIndexOf("EchoLink directory status changed to ?"), data.lastIndexOf("Disconnected from EchoLink proxy"));
        if (lastOn > lastOff) { 
            $("#el-live-status").text(T.el_connected).removeClass("el-disconnected").addClass("el-connected"); 
        } else if (lastOff > -1) { 
            $("#el-live-status").text(T.el_disconnected).removeClass("el-connected").addClass("el-disconnected"); 
        }
        let isTalking = false;
        let currentCallsign = "---";
        let currentTG = "";
        let statusText = T.standby;
        let lastStartPos = -1; let lastStopPos = -1;
        let talkerRegex = /Talker start on TG #(\d+): ([A-Z0-9-\/]+)/g;
        while ((match = talkerRegex.exec(data)) !== null) { 
            lastStartPos = match.index; 
            currentTG = match[1]; 
            currentCallsign = match[2]; 
        }
        let stopRegex = /Talker stop on TG/g; 
        while ((match = stopRegex.exec(data)) !== null) { 
            lastStopPos = match.index; 
        }
	let interruptPos = Math.max(
            data.lastIndexOf("ReflectorLogic: Disconnected"),
            data.lastIndexOf("Deactivating module"),
            data.lastIndexOf("SvxLink v"),
            data.lastIndexOf("Selecting TG"),
            data.lastIndexOf("Connection established")
        );
        if (interruptPos > lastStopPos) {
            lastStopPos = interruptPos;
        }
        if (lastStartPos > lastStopPos && lastStartPos !== -1) {
            isTalking = true;
            statusText = T.tx; 
        }
        let lastTxOn = data.lastIndexOf("Tx1: Turning the transmitter ON");
        let lastTxOff = data.lastIndexOf("Tx1: Turning the transmitter OFF");
        if (lastTxOn > lastTxOff && lastTxOn !== -1) {
            if(!isTalking) {
                isTalking = true;
                statusText = T.tx; 
            }
        }
        let lastSqOpen = data.lastIndexOf("Rx1: The squelch is OPEN");
        let lastSqClose = data.lastIndexOf("Rx1: The squelch is CLOSED");
        let sqInterrupt = Math.max(
            data.lastIndexOf("ReflectorLogic: Disconnected"),
            data.lastIndexOf("Selecting TG")
        );
        if (sqInterrupt > lastSqClose) { lastSqClose = sqInterrupt; }

        if (lastSqOpen > lastSqClose && lastSqOpen !== -1) {
            isTalking = true;
            statusText = T.rx_local;
            currentCallsign = "LOKALNIE"; 
        }
        $(".live-box").removeClass("talking rx-active tx-active");
        
        if (isTalking) {
            $(".live-status").text(statusText);
            $(".live-callsign").text(currentCallsign);
            if(currentTG) $(".live-tg").text("TG " + currentTG).css("color", "#FF9800");

            let infoText = "";
            if (currentCallsign !== "LOKALNIE" && currentCallsign !== "---" && cachedNodesData[currentCallsign]) {
                let node = cachedNodesData[currentCallsign];
                let name = node.Sysop || node.sysop || "";
                let city = node.Location || node.nodeLocation || "";
                if (!city && node.qth && node.qth.length > 0 && node.qth[0].name) {
                    city = node.qth[0].name;
                }

                if (name && city) {
                    infoText = name + " • " + city;
                } else if (name) {
                    infoText = name;
                } else if (city) {
                    infoText = city;
                }
            }
            $(".live-info").text(infoText);

            if (statusText.includes("RX") || statusText.includes("RECEIVING") || statusText.includes("ODBIERANIE")) {
                $(".live-box").addClass("rx-active");
                $(".live-status, .live-callsign").css("color", "#4CAF50");
                $(".live-info").css("color", "#81C784");
                $("#smart-eq").css("display", "flex").removeClass("eq-tx").addClass("eq-rx");
            } else {

                $(".live-box").addClass("tx-active");
                $(".live-status, .live-callsign").css("color", "#FF9800");
                $(".live-info").css("color", "#FFCC80");
                $("#smart-eq").css("display", "flex").removeClass("eq-rx").addClass("eq-tx");
            }
        } else {

            $(".live-status").text(T.standby).css("color", "#666");
            $(".live-callsign").text("---").css("color", "#fff");
            $(".live-info").text("");
            $(".live-tg").text("");

            $("#smart-eq").hide();
        }
    });
    $.get('last_heard.php?t=' + Date.now(), function(data) { $('#lh-content').html(data); });
}

var cachedNodesData = {};
function updateNodes() {
    $.getJSON('nodes.php', function(data) {
        if (!data || !data.nodes) return;
        cachedNodesData = data.nodes;
        var myCall = GLOBAL_CALLSIGN;
        var nodeKeys = Object.keys(data.nodes).sort();
        $("#btn-Nodes").text(T.nodes_tab + " (" + nodeKeys.length + ")");
        var html = "";
        if (nodeKeys.length === 0) {
        html = "<div style='grid-column:1/-1;text-align:center;color:#777;'>" + T.no_nodes + "</div>";
    } else {
        nodeKeys.forEach(function(call) {
            var n = data.nodes[call];
            var isMe = (call === myCall);
            var isTx = (n.isTalker === true || n.isTalker === "1" || n.isTalker === 1) ? " node-tx" : ""; 
            var cssClass = "node-item" + (isMe ? " is-me" : "") + isTx;
            var swIcon = getSwIcon(n.sw); 

            html += `<div class="${cssClass}" onclick="handleNodeClick(event, '${call}')" onmouseenter="showTooltip(event, '${call}')" onmouseleave="hideTooltip()" onmousemove="moveTooltip(event)">
                        <span class="node-icon">${swIcon}</span>
                        <span class="node-name">${call}</span>
                     </div>`;
        });
    }
    $("#nodes-content").html(html);
    });
}
function getRadioInfo(n) {
    let freq = "";
    let ctcss = "";

    if (n.RXFREQ && parseFloat(n.RXFREQ) > 0) freq = n.RXFREQ;
    if (n.CTCSS) ctcss = n.CTCSS.toString();
    if (!freq && n.qth && n.qth.length > 0) {
        let q = n.qth[0];
        if (q.rx && q.rx.R && q.rx.R.freq) freq = q.rx.R.freq;
        if (q.rx && q.rx.R && q.rx.R.ctcssFreq) ctcss = q.rx.R.ctcssFreq.toString();
    }

    if (freq) {
        let f = parseFloat(freq).toFixed(3);
        let result = f + " MHz";
        if (ctcss && parseFloat(ctcss) > 0) {
            let c = parseFloat(ctcss);
            if (c > 250) c = c / 10.0;
            result += " (CTCSS: " + c.toFixed(1) + " Hz)";
        }
        return result;
    }
    return "";
}

function getSwIcon(sw) {
    if (!sw) return "📻";
    let s = sw.toLowerCase();
    if (s.includes("hamlink") || s.includes("qsolink") || s.includes("latry") || s.includes("zello")) return "📱";
    if (s.includes("linkify") || s.includes("desktop") || s.includes("pc")) return "💻";
    return "📻";
}
function showTooltip(e, callsign) {
    if (!cachedNodesData[callsign]) return;
    var info = cachedNodesData[callsign];
    var tooltip = document.getElementById('node-tooltip');
    if (tooltip.parentNode !== document.body) {
        document.body.appendChild(tooltip);
    }
    $("#nt-callsign").text(callsign);
    $("#nt-sw").text((info.sw || "") + " " + (info.swVer || ""));
    var name = "---";
    if (info.Sysop) {
         name = info.Sysop;
    } else if (info.sysop) {
         name = info.sysop;
    } else if (info.qth && info.qth.length > 0 && info.qth[0].name) {
         name = info.qth[0].name;
    }
    $("#nt-name").text(name);
    var activeTg = (info.tg && info.tg !== 0) ? info.tg : T.no_tg;
    $("#nt-tg").text(activeTg);
    var locator = "---";
    if (info.Locator) {
        locator = info.Locator;
    } else if (info.qth && info.qth.length > 0 && info.qth[0].pos && info.qth[0].pos.loc) {
        locator = info.qth[0].pos.loc;
    }
    $("#nt-qth").text(locator);
    var location = "---";
    if (info.Location) {
        location = info.Location;
    } else if (info.nodeLocation) {
        location = info.nodeLocation;
    }
    $("#nt-loc").text(location);
    var monitored = "---";
    if (info.monitoredTGs && Array.isArray(info.monitoredTGs) && info.monitoredTGs.length > 0) {
        monitored = info.monitoredTGs.join(", ");
    }
    $("#nt-monitored").text(monitored);
    $("#nt-ver").text(info.projVer || "---");
    var radioData = getRadioInfo(info);
    if(radioData) {
        $("#nt-radio").text(radioData);
        $("#row-radio").show();
    } else {
        $("#row-radio").hide();
    }

    tooltip.style.display = 'block';
    moveTooltip(e);
}

function moveTooltip(e) {
    var tooltip = document.getElementById('node-tooltip');
    if(tooltip.style.display === 'block') {
        
        var ttWidth = tooltip.offsetWidth;
        var ttHeight = tooltip.offsetHeight;
        var x = e.clientX + 15;
        var y = e.clientY + 15;
        if (x + ttWidth > window.innerWidth) { 
            x = e.clientX - ttWidth - 15; 
        }
        if (y + ttHeight > window.innerHeight) { 
            y = e.clientY - ttHeight - 15; 
        }
        if (x < 5) x = 5;
        if (y < 5) y = 5;

        tooltip.style.left = x + 'px';
        tooltip.style.top = y + 'px';
    }
}

function hideTooltip() {
    document.getElementById('node-tooltip').style.display = 'none';
}

setInterval(loadLogsAndStatus, 1000);
setInterval(updateStats, 10000);
setInterval(updateNodes, 15000);
loadLogsAndStatus();
updateStats();
updateNodes();

var mapInstance = null;
function qthToLatLon(qth) {
    qth = qth.toUpperCase();
    if (!/^[A-R]{2}[0-9]{2}[A-X]{2}$/.test(qth)) return null;
    var lon = (qth.charCodeAt(0) - 65) * 20 - 180 + (parseInt(qth.charAt(2)) * 2) + ((qth.charCodeAt(4) - 65) + 0.5) / 12;
    var lat = (qth.charCodeAt(1) - 65) * 10 - 90 + parseInt(qth.charAt(3)) + ((qth.charCodeAt(5) - 65) + 0.5) / 24;
    return [lat, lon];
}
    function openGridMapper() {
    var overlay = document.getElementById('map-overlay');
    if (overlay.parentNode !== document.body) {
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex'; 
    
    var style = localStorage.getItem('mapStyle') || 'dark';
    var tileUrl = '';
    var tileOptions = {
        maxZoom: 19,
        minZoom: 2,
        noWrap: true,
        attribution: '&copy; OpenStreetMap contributors'
    };
    
    if(style === 'light') {
        tileUrl = 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
        tileOptions.subdomains = 'abcd';
        tileOptions.attribution += ' &copy; CARTO';
    } else if(style === 'osm') {
        tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        tileOptions.subdomains = 'abc';
    } else {
        tileUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
        tileOptions.subdomains = 'abcd';
        tileOptions.attribution += ' &copy; CARTO';
    }
    
    if (mapInstance) {
        mapInstance.remove();
        mapInstance = null;
    }
    
    mapInstance = L.map('map-container', {
        maxBounds: [
            [-90, -180],
            [90, 180]
        ],
        maxBoundsViscosity: 1.0
    }).setView([52.0, 19.0], 6);
    
    L.tileLayer(tileUrl, tileOptions).addTo(mapInstance);
    
    var nodes = cachedNodesData;
    if (!nodes) return;
    
   Object.keys(nodes).forEach(function(callsign) {
        var n = nodes[callsign];
        var loc = "";
        if (n.Locator) loc = n.Locator;
        else if (n.qth && n.qth.length > 0 && n.qth[0].pos) loc = n.qth[0].pos.loc;
        
        if (loc) {
            var coords = qthToLatLon(loc);
            if (coords) {
                var radioData = getRadioInfo(n);
                var popupContent = "<div style='text-align:center; min-width:120px;'><b>" + callsign + "</b><br>" + loc;
                if(n.Sysop) popupContent += "<br><span style='color:#bbb; font-size:11px;'>" + n.Sysop + "</span>";
                if(radioData) popupContent += "<br><br><span style='color:#0288d1; font-weight:bold; font-size:12px;'>QRG: " + radioData + "</span>";
                var popupContent = "<div style='text-align:center; min-width:120px;'><b>" + callsign + "</b><br>" + loc;
                if(n.Sysop) popupContent += "<br><span style='color:#bbb; font-size:11px;'>" + n.Sysop + "</span>";
                if(radioData) popupContent += "<br><br><span style='color:#0288d1; font-weight:bold; font-size:12px;'>QRG: " + radioData + "</span>";
                var popupContent = "<div style='text-align:center; min-width:120px;'><b>" + callsign + "</b><br>" + loc;
                if(n.Sysop) popupContent += "<br><span style='color:#bbb; font-size:11px;'>" + n.Sysop + "</span>";
                if(radioData) popupContent += "<br><br><span style='color:#0288d1; font-weight:bold; font-size:12px;'>" + radioData + "</span>";
                var activeTg = (n.tg && n.tg !== 0) ? n.tg : "";
                var isTx = (n.isTalker === true || n.isTalker === "1" || n.isTalker === 1);
                if (isTx && activeTg) {
                    popupContent += "<br><span style='display:inline-block; margin-top:8px; background:#F44336; color:#fff; padding:3px 8px; border-radius:4px; font-weight:bold; font-size:11px; animation: tx-pulse-bg 1.5s infinite;'>🎙️ Nadaje na TG " + activeTg + "</span>";
                }
		popupContent += "</div>";

                var isTx = (n.isTalker === true || n.isTalker === "1" || n.isTalker === 1) ? " map-tx" : "";
                var swIcon = getSwIcon(n.sw);

                var customIcon = L.divIcon({
                    className: 'custom-map-marker' + isTx,
                    html: swIcon,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                    popupAnchor: [0, -18]
                });

                L.marker(coords, {icon: customIcon}).addTo(mapInstance).bindPopup(popupContent);
            }
        }
    });
    
    setTimeout(function(){
        mapInstance.invalidateSize();
        mapInstance.setView([52.0, 19.0], 5);
    }, 450);
}

    function closeGridMapper() {
    document.getElementById('map-overlay').style.display = 'none';
}

function handleNodeClick(e, callsign) {
    var node = cachedNodesData[callsign];
    if (!node) return;

    var isTx = (node.isTalker === true || node.isTalker === "1" || node.isTalker === 1);
    var activeTg = (node.tg && node.tg !== 0) ? node.tg : "";

    if (isTx && activeTg) {
        var menu = document.getElementById('node-action-menu');
        if (menu.parentNode !== document.body) {
            document.body.appendChild(menu);
        }

        document.getElementById('nam-title').innerHTML = "<span style='color:#FF9800;'>" + callsign + "</span> nadaje!";
        var btnTg = document.getElementById('nam-btn-tg');
        btnTg.innerText = "🎙️ Przełącz na TG " + activeTg;
        btnTg.onclick = function() {
            sendAjax("*91" + activeTg + "#");
            closeNodeActionMenu();
            window.scrollTo({ top: 0, behavior: 'smooth' }); 
        };

        var btnQrz = document.getElementById('nam-btn-qrz');
        btnQrz.onclick = function() {
            window.open('https://www.qrz.com/db/' + callsign, '_blank');
            closeNodeActionMenu();
        };

        var x = e.clientX + 10;
        var y = e.clientY + 10;
        menu.style.display = 'block';
        menu.classList.add('active-menu-anim');
        var mWidth = menu.offsetWidth;
        var mHeight = menu.offsetHeight;
        if (x + mWidth > window.innerWidth) x = e.clientX - mWidth - 15;
        if (y + mHeight > window.innerHeight) y = e.clientY - mHeight - 15;
        if (x < 5) x = 5;
        if (y < 5) y = 5;

        menu.style.left = x + 'px';
        menu.style.top = y + 'px';
    } else {
        window.open('https://www.qrz.com/db/' + callsign, '_blank');
    }
}

function closeNodeActionMenu() {
    var menu = document.getElementById('node-action-menu');
    if(menu) {
        menu.style.display = 'none';
        menu.classList.remove('active-menu-anim');
    }
}

document.addEventListener('click', function(e) {
    var menu = document.getElementById('node-action-menu');
    if (menu && menu.style.display === 'block') {
        if (!menu.contains(e.target) && !e.target.closest('.node-item')) {
            closeNodeActionMenu();
        }
    }
});

let tgCurrentTargetId = '';
let tgCurrentMode = 'multi';
let tgSelectedArray = [];

function openTgSelector(targetInputId, mode) {
    tgCurrentTargetId = targetInputId;
    tgCurrentMode = mode;
    
    document.getElementById('tg-modal-mode').innerText = (mode === 'single') ? T.tg_mode_single : T.tg_mode_multi;
    
    let currentVal = document.getElementById(targetInputId).value.trim();
    if (currentVal) {
        tgSelectedArray = currentVal.split(',').map(s => s.trim()).filter(s => s !== "");
    } else {
        tgSelectedArray = [];
    }
    
    renderTgChips();
    renderTgLists();
    
    document.getElementById('tg-modal-overlay').style.display = 'flex';
}

function closeTgSelector() {
    document.getElementById('tg-modal-overlay').style.display = 'none';
}

function saveTgSelection() {
    document.getElementById(tgCurrentTargetId).value = tgSelectedArray.join(',');
    closeTgSelector();
}

function addManualTg() {
    let input = document.getElementById('tg-manual-input');
    let val = input.value.trim();
    if (val) {
        addTgToSelection(val);
        input.value = '';
    }
}

function addTgToSelection(tgNum) {
    if (tgCurrentMode === 'single') {
        tgSelectedArray = [tgNum];
    } else {
        if (!tgSelectedArray.includes(tgNum)) {
            tgSelectedArray.push(tgNum);
        }
    }
    renderTgChips();
}

function removeTgFromSelection(tgNum) {
    tgSelectedArray = tgSelectedArray.filter(t => t !== tgNum);
    renderTgChips();
}

function renderTgChips() {
    let container = document.getElementById('tg-selected-container');
    container.innerHTML = '';
    
    if (tgSelectedArray.length === 0) {
        container.innerHTML = '<span style="color:#555; font-style:italic;">' + T.tg_empty_sel + '</span>';
        return;
    }
    
    tgSelectedArray.forEach(tg => {
        let chip = document.createElement('div');
        chip.className = 'tg-chip';
        chip.innerHTML = `
            TG ${tg}
            <div class="tg-chip-del" onclick="removeTgFromSelection('${tg}')">✕</div>
        `;
        container.appendChild(chip);
    });
}

function renderTgLists() {
    let container = document.getElementById('tg-lists-container');
    container.innerHTML = '';

    if (typeof tgDataGroups === 'undefined' || !tgDataGroups || tgDataGroups.length === 0) {
        container.innerHTML = '<div style="text-align:center; color:#777; padding:20px;">' + T.tg_no_data + '</div>';
        return;
    }
    
    tgDataGroups.forEach(group => {
        if (!group.buttons || group.buttons.length === 0) return;
        
        let validButtons = group.buttons.filter(b => b.tg && b.tg.trim() !== "");
        if (validButtons.length === 0) return;
        
        let title = document.createElement('div');
        title.className = 'tg-cat-title';
        title.innerText = group.name;
        container.appendChild(title);
        
        let grid = document.createElement('div');
        grid.className = 'tg-grid';
        
        validButtons.forEach(btn => {
            let tile = document.createElement('div');
            tile.className = 'tg-tile';
            tile.onclick = () => addTgToSelection(btn.tg);
            tile.innerHTML = `
                <div class="tg-tile-num">${btn.tg}</div>
                <div class="tg-tile-name">${btn.name}</div>
            `;
            grid.appendChild(tile);
        });
        
        container.appendChild(grid);
    });
}
