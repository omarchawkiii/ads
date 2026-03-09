/**
 * helper.js — AdSmart admin helpers
 * getStatusText / getStatusAction used by get_compaigns() in index.blade.php
 */

function getStatusText(status) {
    var map = {
        1: '<span class="badge bg-warning text-dark">Pending</span>',
        2: '<span class="badge bg-success">Approved</span>',
        3: '<span class="badge bg-secondary">Draft</span>',
        4: '<span class="badge bg-danger">Rejected</span>',
    };
    return map[status] || '<span class="badge bg-light text-dark">Unknown</span>';
}

/**
 * Returns Approve / Reject buttons based on status.
 * Pending(1) / Draft(3) → Approve + Reject
 * Approved(2)           → Reject only
 * Rejected(4)           → Approve only
 */
function getStatusAction(status, id) {
    var approveBtn =
        '<button data-bs-toggle="tooltip" title="Approve" data-id="' + id + '" type="button"' +
        ' class="approuve btn btn-sm btn-rounded btn-success m-1">' +
        '<i class="mdi mdi-check" style="pointer-events:none"></i></button>';

    var rejectBtn =
        '<button data-bs-toggle="tooltip" title="Reject" data-id="' + id + '" type="button"' +
        ' class="reject btn btn-sm btn-rounded btn-outline-warning m-1">' +
        '<i class="mdi mdi-close" style="pointer-events:none"></i></button>';

    if (status == 1) return approveBtn + rejectBtn;
    if (status == 2) return rejectBtn;
    if (status == 3) return approveBtn + rejectBtn;
    if (status == 4) return approveBtn;
    return '';
}
