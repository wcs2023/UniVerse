:root {
  --primary-purple: #6b46c1;
  --secondary-purple: #8b5cf6;
  --light-purple: #a78bfa;
  --dark-purple: #553c9a;
  --purple-gradient: linear-gradient(135deg, #6b46c1, #8b5cf6);
  --pastel-purple-gradient: linear-gradient(135deg, #C4B5FD, #DDD6FE, #EDE9FE);
  --text-dark: #1f2937;
  --text-light: #6b7280;
  --white: #ffffff;
  --light-gray: #f9fafb;
  --border-color: #e5e7eb;
  --success-color: #10b981;
  --error-color: #ef4444;
  --warning-color: #f59e0b;
}


* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  line-height: 1.6;
  color: var(--text-dark);
  background: var(--pastel-purple-gradient);
  min-height: 100vh;

}

.container {
  max-width: 1200px;
  margin: 2rem auto;

}

.header {
  margin-bottom: 3rem;
}

.header h1 {
  font-size: 2.5rem;
  margin-bottom: 2rem;
  background: var(--purple-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.input-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.input-card {
  background: var(--white);
  padding: 1.5rem;
  border-radius: 12px;
  border-left: 4px solid var(--secondary-purple);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.input-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(107, 70, 193, 0.12);
}

.input-label {
  font-size: 0.85rem;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.input-value {
  font-size: 2rem;
  font-weight: 700;
  color: var(--primary-purple);
}

/* Recommendations Table */
.recommendations-section {
  background: var(--white);
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.section-header {
  padding: 2rem;
  background: var(--pastel-purple-gradient);
  border-bottom: 2px solid rgba(107, 70, 193, 0.1);
}

.section-header h2 {
  font-size: 1.75rem;
  color: var(--primary-purple);
  margin: 0;
}

.table-wrapper {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: linear-gradient(135deg, #f3f0ff 0%, #faf5ff 100%);
}

th {
  padding: 1.25rem;
  text-align: left;
  font-weight: 600;
  color: var(--primary-purple);
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border-color);
}

td {
  padding: 1.5rem 1.25rem;
  border-bottom: 1px solid var(--border-color);
  color: var(--text-dark);
}

tbody tr {
  transition: background-color 0.2s ease;
}

tbody tr:hover {
  background-color: #faf5ff;
}

tbody tr:last-child td {
  border-bottom: none;
}

/* University Badge */
.university-badge {
  display: inline-block;
  background: var(--purple-gradient);
  color: var(--white);
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.9rem;
}

.btn {
  padding: 6px 10px;
  cursor: pointer;
  border: 1px solid #999;
  background: #fff;
  border-radius: 6px;
}

.btn:hover {
  background: #f6f6f6;
}

.btn-sm {
  padding: 4px 8px;
  font-size: 0.9rem;
}

.back-link {
  margin-top: 1rem;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-window {
  background: #fff;
  width: min(600px, 92vw);
  border-radius: 10px;
  padding: 16px 16px 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header h3 {
  margin: 0;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  line-height: 1;
}

.details-list {
  margin: 0;
}

.details-list dt {
  font-weight: bold;
  margin-top: 8px;
}

.modal-footer {
  text-align: right;
  margin-top: 1rem;
}

/* details formatting */
#md-details {
  white-space: pre-wrap;
}

/* visible state */
.modal-backdrop.is-open {
  display: flex;
}

/* Action Button */
/* .action-btn {
            background: linear-gradient(135deg, var(--secondary-purple), var(--light-purple));
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        } */

/* Placeholder Content Styling */
.placeholder {
  color: var(--text-light);
  font-style: italic;
}

@media (max-width: 768px) {

  .header h1 {
    font-size: 1.75rem;
  }

  .input-grid {
    grid-template-columns: 1fr 1fr;
  }

  table {
    font-size: 0.9rem;
  }

  th,
  td {
    padding: 1rem 0.75rem;
  }
}

----------------view--------------------------------------------------------------------------------------------------

<div class="container">
        <div class="header">
        <h1>Recommendation</h1>
        <?php
            $zscore = (string)($zscore ?? '');
            $stream = (string)($stream ?? '');
            $district = (string)($district ?? '');
        ?>
        <div class="input-grid">
    <div class="input-card">
       <div class="input-label"> Z-score</div> 
       <div class="input-value"><strong>6646431</strong> </div>
    </div>
    <div class="input-card">
       <div class="input-label"> Stream</div> 
       <div class="input-value"><strong>flnklknl</strong> </div>
    </div>
    <div class="input-card">
       <div class="input-label"> District</div> 
       <div class="input-value"><strong>oihoilf</strong> </div>
    </div>
  </div>
</div>
  
    <!-- <div class="alert">
        No mathches found for the given criteria. Please try different filters.
    </div> -->
    
        <div class="recommendation-section">
            <div class="section-header">
                <h2>Recommended Universities and Courses</h2>
            </div>
        <div class="table-wrapper">
            <table>
            <thead>
                <tr>
                    <th>Uni code</th>
                    <th>University</th>
                    <th>Degree Name</th>
                    <th>Cutoff Mark</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                
                    <tr>
                        <td>
                            <span class="placeholder">1234</span>
                        </td>
                        <td><span class="university-badge">uoc</span></td>
                        <td><span class="placeholder">degree name</span></td>
                        <td><span class="placeholder">cutoff</span></td>
                        <td>
                            <button type="button" class="btn btn-sm view-details-btn"  aria-haspopup="dialog"
                            aria-control="degreeDetailModal">View Details</button>
                        </td>
                    </tr>
               
            </tbody>
            </table>
        </div>
        </div>

       

        <p class="back-link">
            <a href="<?=BASE_URL?>/degree_suggestion.view.php">Back</a>
        </p>
    </div>

    <!-- reusable modal -->

    <div class="modal-backdrop" id="degreeDetailModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-window" role ="document">
            <div class="modal-header">
                <h3 id="modalTitle">Degree Details</h3>
                <button type="button" class="modal-close" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <dl class="details-list">
                    <dt>Unicode</dt>
                    <dd id="md-unicode"></dd>
                    <dt>University</dt>
                    <dd id="md-university"></dd>
                    <dt>Course Name</dt>
                    <dd id="md-course"></dd>
                    <dt>Cutoff Marks</dt>
                    <dd id="md-cutoff"></dd>   
                    <dt>Details</dt>
                    <dd id="md-details"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn modal-close">Close</button>
            </div>
        </div>
    </div>