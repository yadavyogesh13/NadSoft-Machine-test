let currentPage = 1;
const limit = 5; // Showing 5 per page for easier testing of pagination

$(document).ready(function () {
    // Initial Load
    loadBusinesses(currentPage);

    // Business Form Submission (Add/Edit)
    $('#businessForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#businessId').val();
        const action = id ? 'update' : 'create';
        const formData = $(this).serialize();

        $.ajax({
            url: `app/Controllers/BusinessController.php?action=${action}`,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#businessModal').modal('hide');
                    alert(response.message);
                    loadBusinesses(currentPage);
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // Rating Form Submission
    $('#ratingForm').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const businessId = $('#ratingBusinessId').val();

        $.ajax({
            url: 'app/Controllers/RatingController.php?action=submit',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#ratingModal').modal('hide');
                    alert(response.message);
                    // Log debug query if provided
                    if (response.debug_query) {
                        console.log("SQL Query executed:", response.debug_query);
                    }

                    // Update specific row average rating without full reload
                    updateRowRating(businessId, response.average_rating);
                } else {
                    alert(response.message);
                }
            }
        });
    });

    // Reset Business Modal on Add
    $('#addBusinessBtn').on('click', function () {
        $('#businessModalLabel').text('Add Business');
        $('#businessForm')[0].reset();
        $('#businessId').val('');
    });

    // Handle Edit Click (Event Delegation)
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `app/Controllers/BusinessController.php?action=readOne&id=${id}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const b = response.data;
                    $('#businessModalLabel').text('Edit Business');
                    $('#businessId').val(b.id);
                    $('[name="name"]').val(b.name);
                    $('[name="address"]').val(b.address);
                    $('[name="phone"]').val(b.phone);
                    $('[name="email"]').val(b.email);
                    $('#businessModal').modal('show');
                }
            }
        });
    });

    // Handle Delete Click (Event Delegation)
    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this business?')) {
            $.ajax({
                url: 'app/Controllers/BusinessController.php?action=delete',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $(`#row-${id}`).fadeOut(300, function () {
                            $(this).remove();
                            loadBusinesses(currentPage); // Refresh pagination
                        });
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    // Handle Rating Modal Open (Event Delegation)
    $(document).on('click', '.rating-display', function () {
        const id = $(this).data('id');
        // 1. Reset Form
        $('#ratingForm')[0].reset();
        $('#ratingBusinessId').val(id);

        // 2. Nuclear Reset: Remove the element and re-insert it
        $('#raty-plugin-wrapper').empty().append('<div id="raty-plugin"></div>');

        // 3. Initialize fresh Raty instance
        $('#raty-plugin').raty({
            path: 'assets/images/raty',
            starOn: 'star-on.png',
            starOff: 'star-off.png',
            starHalf: 'star-half.png',
            scoreName: 'rating',
            score: 0,
            half: true,
            targetKeep: true
        });

        $('#ratingModal').modal('show');
    });

    // Pagination Click Handler (Event Delegation)
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const targetPage = $(this).data('page');
        if (targetPage && targetPage !== currentPage) {
            loadBusinesses(targetPage);
        }
    });
});

function loadBusinesses(page = 1) {
    currentPage = page;
    $.ajax({
        url: `app/Controllers/BusinessController.php?action=read&page=${page}&limit=${limit}`,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                renderTable(response.data);
                renderPagination(response.pagination);
            }
        }
    });
}

function renderPagination(pagination) {
    const totalPages = pagination.pages;
    const page = pagination.page;
    let html = '';

    // Previous Button
    html += `
        <li class="page-item ${page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${page - 1}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;

    // Page Numbers
    for (let i = 1; i <= totalPages; i++) {
        html += `
            <li class="page-item ${page === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
    }

    // Next Button
    html += `
        <li class="page-item ${page === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${page + 1}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;

    $('#pagination').html(html);
}

function renderTable(data) {
    let rows = '';
    if (data.length === 0) {
        rows = `
            <tr>
                <td colspan="7" class="text-center">
                    No Data Available
                </td>
            </tr>
        `;
    } else {
        data.forEach((item, index) => {
            const srNo = (currentPage - 1) * limit + index + 1;
            rows += `
                <tr id="row-${item.id}">
                    <td class="ps-4 text-muted small">${srNo}</td>
                    <td class="fw-bold">${item.name}</td>
                    <td><span class="text-truncate d-inline-block" style="max-width: 200px;">${item.address || '-'}</span></td>
                    <td>${item.phone || '-'}</td>
                    <td>${item.email || '-'}</td>
                    <td>
                        <div class="rating-display" data-id="${item.id}" data-score="${item.average_rating}">
                            <div class="raty-read-only" data-score="${item.average_rating}"></div>
                            <span class="ms-1 small fw-bold text-warning">${parseFloat(item.average_rating).toFixed(1)}</span>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-primary me-1 edit-btn" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    $('#businessList').html(rows);

    // Initialize read-only Raty for each row
    $('.raty-read-only').each(function () {
        const score = $(this).data('score');
        $(this).html(''); // Clear any existing stars
        $(this).raty({
            readOnly: true,
            path: 'assets/images/raty',
            starOn: 'star-on.png',
            starOff: 'star-off.png',
            starHalf: 'star-half.png',
            score: score,
            half: true
        });
    });
}

function updateRowRating(businessId, averageRating) {
    const row = $(`#row-${businessId}`);
    if (row.length) {
        const ratingContainer = row.find('.rating-display');
        const ratyElem = ratingContainer.find('.raty-read-only');
        const score = parseFloat(averageRating);

        // Update data-score attribute
        ratingContainer.attr('data-score', score);

        // 1. Create a brand new clean div
        const newRaty = $('<div class="raty-read-only"></div>');
        newRaty.attr('data-score', score);

        // 2. Replace the old one (this destroys all plugin state)
        ratyElem.replaceWith(newRaty);

        // 3. Initialize the brand new element
        newRaty.raty({
            readOnly: true,
            path: 'assets/images/raty',
            starOn: 'star-on.png',
            starOff: 'star-off.png',
            starHalf: 'star-half.png',
            score: score,
            half: true
        });

        // 4. Update the numerical text
        ratingContainer.find('span.text-warning').text(score.toFixed(1));
    }
}