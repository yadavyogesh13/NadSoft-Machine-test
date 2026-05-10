<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Listing & Rating System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/jquery.raty.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"></i>Business Listing Page</a>
            <button class="btn btn-light btn-sm fw-600" data-bs-toggle="modal" data-bs-target="#businessModal" id="addBusinessBtn">
                <i class="fas fa-plus me-1"></i> Add Business
            </button>
        </div>
    </nav>

    <main class="container my-5">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="mb-0 fw-bold text-dark">Business Directory</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="businessTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Sr. No.</th>
                            <th>Business Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Avg Rating</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="businessList">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white py-3 border-top-0">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0" id="pagination">
                        <!-- Dynamic Pagination -->
                    </ul>
                </nav>
            </div>
        </div>
    </main>

    <!-- Business Modal (Add/Edit) -->
    <div class="modal fade" id="businessModal" tabindex="-1" aria-labelledby="businessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="businessModalLabel">Add Business</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="businessForm">
                    <div class="modal-body p-4">
                        <input type="hidden" id="businessId" name="id">
                        <div class="mb-3">
                            <label class="form-label fw-600 small">Business Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="Enter business name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600 small">Address</label>
                            <textarea class="form-control" name="address" rows="2" placeholder="Street, City, Zip"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small">Phone</label>
                                <input type="tel" class="form-control" name="phone" pattern="[0-9]{10}" maxlength="10" minlength="10" placeholder="10-digit number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="info@biz.com">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 bg-light p-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Business</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold" id="ratingModalLabel">Submit Rating</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ratingForm">
                    <div class="modal-body p-4">
                        <input type="hidden" id="ratingBusinessId" name="business_id">
                        <div class="mb-4 text-center">
                            <h6 class="fw-bold mb-3">Rate your experience</h6>
                            <div id="raty-plugin-wrapper">
                                <div id="raty-plugin"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600 small">Full Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="Your name">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small">Email *</label>
                                <input type="email" class="form-control" name="email" required placeholder="your@email.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small">Phone *</label>
                                <input type="tel" class="form-control" name="phone" required pattern="[0-9]{10}" maxlength="10" minlength="10" placeholder="10-digit number">
                            </div>
                        </div>
                        <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i> Both email and phone are required to save your rating.</p>
                    </div>
                    <div class="modal-footer border-top-0 bg-light p-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning px-4">Submit Rating</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raty/3.1.1/jquery.raty.min.js"></script>
    <script src="assets/js/app.js?v=<?php echo time(); ?>"></script>
</body>

</html>