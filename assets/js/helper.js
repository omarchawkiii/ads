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



function getStatusText(status) {
    switch (status) {
        case 1: return '<span class="badge bg-warning-subtle text-warning">Pending</span> ';
        case 2: return '<span class="badge bg-success-subtle text-success">Approved</span> ';
        case 3: return '<span class="badge bg-secondary-subtle text-secondary">Drafts</span> ';
        case 4: return '<span class="badge bg-danger-subtle text-danger">Rejected</span> ';
        default: return'<span class="badge bg-warning-subtle text-warning">Unknown</span> ';
    }
}


function getStatusAction(status, id ) {
    switch (status) {
        case 1: return '<button   data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-title="Approuve"  id="'+id+'" type="button" class="approuve  ustify-content-center btn mb-1 btn-rounded btn-success  m-1"><i class="mdi mdi-checkbox-marked-circle "></i></button> <button  data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-title="Reject" title="Reject"  title="Reject" id="'+id+'" type="button" class="reject  ustify-content-center btn mb-1 btn-rounded btn-danger  m-1"><i class="mdi mdi-close-octagon "></i></button>       ';
        case 2: return '<button  data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-title="Reject" title="Reject"  id="'+id+'" type="button" class="reject  ustify-content-center btn mb-1 btn-rounded btn-danger  m-1"><i class="mdi mdi-close-octagon "></i></button>       ';
        case 3: return ' ';
        case 4: return '<button  data-bs-toggle="tooltip" data-bs-placement="top"  data-bs-title="Approuve" title="Approuve" id="'+id+'" type="button" class="approuve  ustify-content-center btn mb-1 btn-rounded btn-success  m-1"><i class="mdi mdi-checkbox-marked-circle "></i></button> ';
        default: return'';
    }
}


function getInvoiceStatusText(status) {
    switch (status) {
        case 1: return '<span class="badge bg-warning-subtle text-warning">Pending</span> ';
        case 2: return '<span class="badge bg-success-subtle text-success">Paid</span> ';
        case 3: return '<span class="badge bg-danger-subtle text-danger">Overdue</span> ';
        default: return'<span class="badge bg-warning-subtle text-warning">Unknown</span> ';
    }
}

function getInvoiceNumber(number) {
    return 'INV-'+number;

}

function formatRM(n) {
    n = Number(n || 0);
    return  n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
