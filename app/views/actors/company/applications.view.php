<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications - UniVerse</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/company.css">
    <style>
        .application-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .application-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .modal-close:hover {
            color: #000;
        }

        .applicant-details {
            margin-bottom: 1.5rem;
        }

        .detail-group {
            margin-bottom: 1rem;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            color: #666;
            margin-top: 0.3rem;
            word-break: break-word;
        }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f0f0f0;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            color: black;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #6b46c1;
        }

        .stat-card.pending {
            border: 2px solid #6b46c1;
        }

        .stat-card.shortlisted {
            border: 2px solid #6b46c1;
        }

        .stat-card.hired {
            border: 2px solid #6b46c1;
        }

        .stat-card.rejected {
            border: 2px solid #6b46c1;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/companyHeader.view.php'; ?>

    <main class="main-content">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Applications Overview -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Job Applications</h2>
                <p class="card-subtitle">Manage and track applications from students for your job postings</p>
            </div>
            
            <!-- Statistics Cards -->
            <?php if (!empty($data['applications'])): ?>
                <div class="stats-row">
                    <?php 
                    $statuses = ['pending' => 0, 'shortlisted' => 0, 'hired' => 0, 'rejected' => 0];
                    foreach ($data['applications'] as $app) {
                        $status = $app['status'];
                        if (isset($statuses[$status])) {
                            $statuses[$status]++;
                        }
                    }
                    ?>
                    <div class="stat-card">
                        <div class="stat-number"><?= count($data['applications']) ?></div>
                        <div class="stat-label">Total Applications</div>
                    </div>
                    <div class="stat-card pending">
                        <div class="stat-number"><?= $statuses['pending'] ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card shortlisted">
                        <div class="stat-number"><?= $statuses['shortlisted'] ?></div>
                        <div class="stat-label">Shortlisted</div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Filter Options -->
            <div class="filter-section" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <input type="text" id="applicantSearch" class="form-control" placeholder="Search by name or email..." style="max-width: 300px;">
                
                <select id="positionFilter" class="filter-select form-control" style="max-width: 200px;">
                    <option value="">All Positions</option>
                    <?php if (!empty($data['jobs'])): ?>
                        <?php foreach ($data['jobs'] as $job): ?>
                            <option value="<?= htmlspecialchars($job['title']) ?>">
                                <?= htmlspecialchars($job['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                
                <select id="applicationStatusFilter" class="filter-select form-control" style="max-width: 200px;">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="under_review">Under Review</option>
                    <option value="shortlisted">Shortlisted</option>
                    <option value="interviewed">Interviewed</option>
                    <option value="hired">Hired</option>
                    <option value="rejected">Rejected</option>
                </select>
                
                <button id="resetAppFilters" class="btn btn-secondary" style="margin-left: auto;">Reset Filters</button>
            </div>

            <!-- Applications Table -->
            <?php if (!empty($data['applications'])): ?>
            <table class="table" id="applicationsTable">
                <thead>
                    <tr>
                        <th class="sortable" data-column="applicant" data-order="asc">
                            Applicant 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="position" data-order="asc">
                            Position 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="date" data-order="asc">
                            Applied Date 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th class="sortable" data-column="status" data-order="asc">
                            Status 
                            <span class="sort-arrow">↕</span>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="applicationsTableBody">
                    <?php foreach ($data['applications'] as $application): ?>
                        <tr data-applicant="<?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?>" 
                            data-email="<?= htmlspecialchars($application['email']) ?>" 
                            data-position="<?= htmlspecialchars($application['job_title']) ?>" 
                            data-date="<?= htmlspecialchars($application['applied_at']) ?>" 
                            data-status="<?= htmlspecialchars($application['status']) ?>"
                            data-app-id="<?= $application['application_id'] ?>">
                            <td>
                                <div class="applicant-info">
                                    <strong><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></strong>
                                    <div class="applicant-email"><?= htmlspecialchars($application['email']) ?></div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($application['job_title']) ?></td>
                            <td><?= date('M d, Y', strtotime($application['applied_at'])) ?></td>
                            <td>
                                <span class="badge badge-<?= getBadgeClass($application['status']) ?>">
                                    <?= ucfirst(str_replace('_', ' ', $application['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-primary" onclick="viewApplicationDetails(event, <?= $application['application_id'] ?>)">View</button>

                                    <?php if (!empty($application['user_id'])): ?>
                                        <a class="btn btn-sm btn-secondary" href="<?= BASE_URL ?>/company/applicantProfile/<?= (int)$application['user_id'] ?>">View Profile</a>
                                    <?php endif; ?>
                                    
                                    <?php if ($application['status'] == 'pending' || $application['status'] == 'under_review'): ?>
                                        <button class="btn btn-sm btn-secondary" onclick="updateStatusWithAction(<?= $application['application_id'] ?>, 'shortlisted')">Shortlist</button>
                                    <?php endif; ?>
                                    
                                    <?php if ($application['status'] == 'shortlisted'): ?>
                                        <button class="btn btn-sm btn-info" onclick="updateStatusWithAction(<?= $application['application_id'] ?>, 'interviewed')">Interview</button>
                                    <?php endif; ?>
                                    
                                    <?php if ($application['status'] == 'interviewed'): ?>
                                        <button class="btn btn-sm btn-success" onclick="updateStatusWithAction(<?= $application['application_id'] ?>, 'hired')">Hire</button>
                                    <?php endif; ?>
                                    
                                    <?php if ($application['status'] != 'hired' && $application['status'] != 'rejected'): ?>
                                        <button class="btn btn-sm btn-danger" onclick="updateStatusWithAction(<?= $application['application_id'] ?>, 'rejected')">Reject</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No Applications Yet</h3>
                    <p>When students apply for your job postings, they will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Application Details Modal -->
    <div id="applicationModal" class="application-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Application Details</h3>
                <button class="modal-close" onclick="closeApplicationModal()">&times;</button>
            </div>
            <div id="modalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <?php
    // Helper function for badge classes
    function getBadgeClass($status) {
        switch ($status) {
            case 'pending':
                return 'warning';
            case 'under_review':
                return 'info';
            case 'shortlisted':
                return 'primary';
            case 'interviewed':
                return 'info';
            case 'hired':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }
    ?>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>
    <script>
        // Application Details Modal Functions
        function viewApplicationDetails(event, applicationId) {
            event.preventDefault();
            event.stopPropagation();
            
            const modal = document.getElementById('applicationModal');
            const modalBody = document.getElementById('modalBody');
            
            // Fetch application details
            fetch('<?= BASE_URL ?>/company/getApplicationDetails/' + applicationId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const app = data.application;
                        let actionsHtml = '';
                        
                        // Generate action buttons based on status
                        if (app.status === 'pending' || app.status === 'under_review') {
                            actionsHtml += `<button class="btn btn-secondary" onclick="updateStatusWithAction(${app.application_id}, 'shortlisted')">Shortlist</button>`;
                        }
                        if (app.status === 'shortlisted') {
                            actionsHtml += `<button class="btn btn-info" onclick="updateStatusWithAction(${app.application_id}, 'interviewed')">Move to Interview</button>`;
                        }
                        if (app.status === 'interviewed') {
                            actionsHtml += `<button class="btn btn-success" onclick="updateStatusWithAction(${app.application_id}, 'hired')">Hire</button>`;
                        }
                        if (app.status !== 'hired' && app.status !== 'rejected') {
                            actionsHtml += `<button class="btn btn-danger" onclick="updateStatusWithAction(${app.application_id}, 'rejected')">Reject</button>`;
                        }
                        
                        const html = `
                            <div class="applicant-details">
                                <div class="detail-group">
                                    <div class="detail-label">Applicant Name</div>
                                    <div class="detail-value">${app.first_name} ${app.last_name}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Email</div>
                                    <div class="detail-value"><a href="mailto:${app.email}">${app.email}</a></div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Phone</div>
                                    <div class="detail-value">${app.phone || 'Not provided'}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Job Position</div>
                                    <div class="detail-value"><strong>${app.job_title}</strong></div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">University</div>
                                    <div class="detail-value">${app.university || 'Not provided'}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Degree Program</div>
                                    <div class="detail-value">${app.degree_program || 'Not provided'}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Applied Date</div>
                                    <div class="detail-value">${new Date(app.applied_at).toLocaleDateString()}</div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Current Status</div>
                                    <div class="detail-value">
                                        <span class="badge badge-${getBadgeClassJS(app.status)}">${app.status.replace(/_/g, ' ').toUpperCase()}</span>
                                    </div>
                                </div>
                                
                                ${app.cover_letter ? `
                                    <div class="detail-group">
                                        <div class="detail-label">Cover Letter</div>
                                        <div class="detail-value">${app.cover_letter}</div>
                                    </div>
                                ` : ''}
                                
                                ${app.notes ? `
                                    <div class="detail-group">
                                        <div class="detail-label">Notes</div>
                                        <div class="detail-value">${app.notes}</div>
                                    </div>
                                ` : ''}

                                 ${app.resume_url ? `
                            <div class="detail-group">
                                <div class="detail-label">Resume</div>
                                <div class="detail-value"><a href="${app.resume_url}" target="_blank">View Resume</a></div>
                            </div>
                        ` : ''}

                            </div>
                            
                            <div class="modal-actions">
                                ${actionsHtml}
                                <button class="btn btn-light" onclick="closeApplicationModal()">Close</button>
                            </div>
                        `;
                        
                        modalBody.innerHTML = html;
                        modal.classList.add('show');
                    } else {
                        alert('Error loading application details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching application details');
                });
        }

        function closeApplicationModal() {
            const modal = document.getElementById('applicationModal');
            modal.classList.remove('show');
        }

        function getBadgeClassJS(status) {
            const classes = {
                'pending': 'warning',
                'under_review': 'info',
                'shortlisted': 'primary',
                'interviewed': 'info',
                'hired': 'success',
                'rejected': 'danger'
            };
            return classes[status] || 'secondary';
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('applicationModal');
            if (event.target === modal) {
                closeApplicationModal();
            }
        });

        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileTrigger = document.querySelector('.profile-trigger');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (profileTrigger && dropdownMenu) {
                profileTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    dropdownMenu.classList.remove('active');
                });
                
                // Prevent dropdown from closing when clicking inside
                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Search and Sort Functionality for Applications
            const searchInput = document.getElementById('applicantSearch');
            const positionFilter = document.getElementById('positionFilter');
            const statusFilter = document.getElementById('applicationStatusFilter');
            const resetBtn = document.getElementById('resetAppFilters');
            const tableBody = document.getElementById('applicationsTableBody');
            const sortableHeaders = document.querySelectorAll('#applicationsTable .sortable');
            
            // Check if all elements exist
            if (!searchInput || !positionFilter || !statusFilter || !resetBtn || !tableBody) {
                console.error('Some filter elements are missing');
                return;
            }
            
            // Search and filter functionality
            searchInput.addEventListener('input', filterApplications);
            positionFilter.addEventListener('change', filterApplications);
            statusFilter.addEventListener('change', filterApplications);
            
            // Reset filters
            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                positionFilter.value = '';
                statusFilter.value = '';
                filterApplications();
            });
            
            function filterApplications() {
                const searchTerm = searchInput.value.toLowerCase();
                const positionValue = positionFilter.value;
                const statusValue = statusFilter.value;
                const rows = tableBody.querySelectorAll('tr');
                
                let visibleCount = 0;
                rows.forEach(row => {
                    const applicantName = row.getAttribute('data-applicant');
                    const applicantEmail = row.getAttribute('data-email');
                    const position = row.getAttribute('data-position');
                    const status = row.getAttribute('data-status');
                    
                    // Check if attributes exist
                    if (!applicantName || !applicantEmail || !position || !status) {
                        return;
                    }
                    
                    const matchesSearch = applicantName.toLowerCase().includes(searchTerm) || 
                                         applicantEmail.toLowerCase().includes(searchTerm);
                    const matchesPosition = positionValue === '' || position === positionValue;
                    const matchesStatus = statusValue === '' || status === statusValue;
                    
                    if (matchesSearch && matchesPosition && matchesStatus) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Sorting functionality
            sortableHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.getAttribute('data-column');
                    const currentOrder = this.getAttribute('data-order');
                    const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                    
                    // Reset all headers
                    sortableHeaders.forEach(h => {
                        h.setAttribute('data-order', 'asc');
                        h.classList.remove('sorted-asc', 'sorted-desc');
                    });
                    
                    // Set current header
                    this.setAttribute('data-order', newOrder);
                    this.classList.add(newOrder === 'asc' ? 'sorted-asc' : 'sorted-desc');
                    
                    sortApplicationsTable(column, newOrder);
                });
            });
            
            function sortApplicationsTable(column, order) {
                const rows = Array.from(tableBody.querySelectorAll('tr'));
                
                rows.sort((a, b) => {
                    let aValue = a.getAttribute('data-' + column);
                    let bValue = b.getAttribute('data-' + column);
                    
                    // Check if values exist
                    if (!aValue || !bValue) {
                        return 0;
                    }
                    
                    // Handle date sorting
                    if (column === 'date') {
                        aValue = new Date(aValue);
                        bValue = new Date(bValue);
                        
                        if (order === 'asc') {
                            return aValue - bValue;
                        } else {
                            return bValue - aValue;
                        }
                    }
                    // Handle text sorting
                    else {
                        aValue = aValue.toLowerCase();
                        bValue = bValue.toLowerCase();
                        
                        if (order === 'asc') {
                            return aValue > bValue ? 1 : aValue < bValue ? -1 : 0;
                        } else {
                            return aValue < bValue ? 1 : aValue > bValue ? -1 : 0;
                        }
                    }
                });
                
                // Re-append sorted rows
                rows.forEach(row => tableBody.appendChild(row));
            }
        });

        // Function to update application status with inline action
        function updateStatusWithAction(applicationId, status) {
            const statusLabel = status.replace(/_/g, ' ').toUpperCase();
            if (!confirm(`Are you sure you want to ${status === 'rejected' ? 'REJECT' : 'change status to'} "${statusLabel}"?`)) {
                return;
            }

            const formData = new FormData();
            formData.append('application_id', applicationId);
            formData.append('status', status);

            fetch('<?= BASE_URL ?>/company/updateApplicationStatus', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Application status updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update status'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
