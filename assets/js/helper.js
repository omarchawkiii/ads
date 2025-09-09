function reset_form(selector) {
    $(selector)[0].reset();
    $(selector).removeClass("was-validated");
}





function jn(x) { return Array.isArray(x) ? x.map(i => i?.name).filter(Boolean).join(', ') || '–' : (x?.name || '–'); }
function jv(x) { return (x ?? '') === '' ? '–' : x; }


function formatDateEN(input, { locale = 'en-US', variant = 'short' } = {}) {
    if (!input) return '–';
    const s = String(input).trim();
    const datePart = s.split(/[ T]/)[0];              // garde "YYYY-MM-DD"
    const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(datePart);
    if (!m) return s;

    const d = new Date(+m[1], +m[2] - 1, +m[3]);      // évite les décalages

    if (variant === 'long') {
        // ex en-US: "Sep 4, 2025", en-GB: "4 Sept 2025"
        return d.toLocaleDateString(locale, { day: 'numeric', month: 'short', year: 'numeric' });
    }
    // short = numérique selon la locale (en-US => MM/DD/YYYY, en-GB => DD/MM/YYYY)
    return d.toLocaleDateString(locale, { day: '2-digit', month: '2-digit', year: 'numeric' });
}


function formatHMS(seconds) {
    const sec = Math.max(0, Math.floor(Number(seconds) || 0));
    const h = Math.floor(sec / 3600);
    const m = Math.floor((sec % 3600) / 60);
    const s = sec % 60;
    const pad = n => String(n).padStart(2, '0');
    return `${pad(h)}:${pad(m)}:${pad(s)}`;
}

