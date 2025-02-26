
@extends('layouts.admin.app', [ 'title' => 'Layout'])


@section('content')

<style>
  .btn-wrapper button {
    gap: 5px;
}

.contentBox {
    padding: 24px;
    min-height: 244px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / .1), 0 2px 4px -2px rgb(0 0 0 / .1);
    transition-property: box-shadow;
    transition-timing-function: cubic-bezier(.4,0,.2,1);
    transition-duration: .15s;
}

.contentBox:hover {
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / .1), 0 4px 6px -4px rgb(0 0 0 / .1);
}

.contentBox h3 {
    font-size: 20px;
    font-weight: 600;
    line-height: 28px;
    color: #111827;
    margin: 0;
}

.contentBox p {
    color: #4b5563;
    font-size: 16px;
    font-weight: 400;
    margin-bottom: 0;
    margin-top: 4px;
}

.contentBox .Badge {
    color: #1e40af;
    background-color: #dbeafe;
    padding-inline: 12px;
    padding-block: 4px;
    border-radius: 50px;
    flex: 0 0 auto;
    font-size: 14px;
}

.contentBox ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.contentBox ul li {
    color: rgb(75, 85, 99);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-weight: 400;
    font-size: 14px;
}

.contentBox ul li:last-child {
    margin: 0;
}

.progressBar {
    height: 8px;
    background-color: #e5e7eb;
    border-radius: 50px;
}

.progressBar .progressBarInner {
    background-color: #2563eb;
    height: 8px;
    border-radius: 50px;
}

.ProjectProgress {
    padding: 16px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / .1), 0 2px 4px -2px rgb(0 0 0 / .1);
    margin-bottom: 24px;
}

.progressHead {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.progressHead span {
    font-size: 16px;
    font-weight: 600;
}

.progressHead span:last-child {
    color: #2563eb;
}

.ProjectProgress .progressDetail {
    margin-top: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ProjectProgress .progressDetail span {
    font-size: 14px;
    color: rgb(75, 85, 99);
}

.contentDetailInner {
    display: grid;
    grid-template-columns: 2fr 1fr;
    margin-bottom: 20px;
    gap: 24px;
}

.contentDetailInner .contentDetailBox {
    padding: 16px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / .1), 0 2px 4px -2px rgb(0 0 0 / .1);
}

.contentDetailBox h2 {font-size: 20px;margin-bottom: 16px;font-weight: 600;}

.contentHeadInline {
    display: flex;
    align-items: start;
    justify-content: space-between;
    margin-bottom: 16px;
}

.contentHeadInline h3 {
    font-size: 18px;
    font-weight: 600;
    line-height: 28px;
    margin: 0;
}

.contentHeadInline p {
    font-size: 14px;
    color: rgb(75, 85, 99);
    margin: 0;
}

.contentHeadInline select {
    background-color: rgb(243, 244, 246);
    border-color: rgb(243, 244, 246);
    padding-inline: 12px;
    padding-block: 4px;
    border-radius: 50px;
    font-size: 14px;
    color: rgb(31, 41, 55);
}

.lengthText p {
    font-size: 14px;
    color: rgb(75, 85, 99);
    margin: 0;
}

.contentDetailBox .contentDetailHead {
    padding: 16px;
    border-bottom: 1px solid rgb(229, 231, 235);
}

.contentDetailBody {
    padding: 16px;
}

.contentDetailBody .task {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.contentDetailBody .task h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.contentDetailBody .task button {
    background: none;
    border: 0;
    color: rgb(37, 99, 235);
    font-size: 14px;
}

.contentDetailBody form label {
    display: block;
    width: 100%;
    font-size: 14px;
    color: rgb(55, 65, 81);
    margin: 0;
}

.contentDetailBody form input, .contentDetailBody form textarea, .contentDetailBody form select {
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / .05);
    width: 100%;
    border-radius: 6px;
    margin-top: 4px;
    border: 0;
    outline: none;
}

.taskBtnWrapper {
    margin-top: 16px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
}

.taskBtnWrapper button.btn {
    font-size: 14px;
    font-weight: 600;
}
</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sprinkler System Projects</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
              <div class="text-right btn-wrapper">
                <button type="button" class="btn btn-primary d-inline-flex align-items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list "><rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><path d="M12 11h4"></path><path d="M12 16h4"></path><path d="M8 11h.01"></path><path d="M8 16h.01"></path></svg>Task Dashboard</button>
              </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row contentBoxesWrap">
          <div class="col-lg-4">
              <div class="bg-white rounded-lg contentBox mb-3">
              <div class="d-flex align-items-start mb-3">
                <div>
                  <h3>Warehouse A</h3>
                  <p>Main storage facility sprinkler system</p>
                </div>
                <div class="Badge">38% Complete</div>
              </div>
              <ul>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin "><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                  <span>123 Industrial Park</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 "><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
                  <span>9 Pipes</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock "><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                  <span>Updated 3/15/2024</span>
                </li>
              </ul>
              <div class="mt-3">
                  <div class="progressBar">
                    <div class="progressBarInner" style="width: 38%;"></div>
                  </div>
              </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="bg-white rounded-lg contentBox mb-3">
              <div class="d-flex align-items-start mb-3">
                <div>
                  <h3>Office Complex B</h3>
                  <p>Three-floor office building protection system</p>
                </div>
                <div class="Badge">50% Complete</div>
              </div>
              <ul>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin "><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                  <span>456 Business Center</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 "><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
                  <span>6 Pipes</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock "><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                  <span>Updated 3/14/2024</span>
                </li>
              </ul>
              <div class="mt-3">
                  <div class="progressBar">
                    <div class="progressBarInner" style="width: 50%;"></div>
                  </div>
              </div>
              </div>
          </div>
          <div class="col-lg-4">
              <div class="bg-white rounded-lg contentBox mb-3">
              <div class="d-flex align-items-start mb-3">
                <div>
                  <h3>Retail Space C</h3>
                  <p>Shopping center sprinkler system</p>
                </div>
                <div class="Badge">56% Complete</div>
              </div>
              <ul>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin "><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                  <span>789 Mall Avenue</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 "><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
                  <span>7 Pipes</span>
                </li>
                <li>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock "><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                  <span>Updated 3/13/2024</span>
                </li>
              </ul>
              <div class="mt-3">
                  <div class="progressBar">
                    <div class="progressBarInner" style="width: 56%;"></div>
                  </div>
              </div>
              </div>
          </div>
        </div>
        
        <div class="row ContentDetail">
            <div class="col-md-12">
              <div class="ProjectProgress">
                  <div class="progressHead">
                    <span class="font-semibold">Project Progress</span>
                    <span class="font-bold text-blue-600">38%</span>
                  </div>
                  <div class="progressBar">
                    <div class="progressBarInner" style="width: 38%;"></div>
                  </div>
                  <div class="progressDetail">
                    <span>100′ completed of 260′ total</span>
                    <span>0 issues found</span>
                  </div>
              </div>
              <div class="contentDetailInner">
                <div class="contentDetailBox">
                <h2>Sprinkler System Layout</h2>
                </div>
                <div class="contentDetailBox p-0">
                    <div class="contentDetailHead">
                      <div class="contentHeadInline">
                        <div>
                          <h3>Branch Line 4</h3>
                          <p>Type: Branch Line</p>
                        </div>
                        <select>
                          <option value="not-started">Not Started</option>
                          <option value="in-progress">In Progress</option>
                          <option value="issue">Issue Found</option>
                          <option value="completed">Completed</option>
                        </select>
                      </div>
                      <div class="lengthText"><p>Length: 20′</p></div>
                    </div>
                    <div class="contentDetailBody">
                        <div class="task">
                          <h4>Tasks</h4>
                          <button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus "><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                            Add Task
                          </button>
                        </div>
                        <div class="mt-3">
                          <form action="">
                             <div class="mb-3">
                                <label for="title">Title</label>
                                <input type="text" id="title">
                             </div>
                             <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea id="description" rows="3" required=""></textarea>
                             </div>
                             <div class="mb-3">
                                <label for="assignedUser">Assign To</label>
                                <select id="assignedUser" required="">
                                  <option value="1">John Smith</option>
                                  <option value="2">Sarah Johnson</option>
                                </select>
                             </div>
                             <div>
                             <label for="dueDate">Due Date</label>
                             <input type="date" id="dueDate" required="">
                             </div>
                             <div class="taskBtnWrapper">
                                <button type="button" class="btn btn-default">Cancel</button>
                                <button type="button" class="btn btn-primary">Create Task</button>
                             </div>
                          </form>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
         <!-- <div class="row">
          
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Projects</h3>
                  <a href="javascript:void(0);">View Report</a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">$18,230.00</span>
                    <span>Sales Over Time</span>
                  </p>
                  <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 33.1%
                    </span>
                    <span class="text-muted">Since last month</span>
                  </p>
                </div>

                <div class="position-relative mb-4">
                  <canvas id="projects-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> This year
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> Last year
                  </span>
                </div>
              </div>
            </div>
          </div>
          </div> -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 

   
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@endsection

@push('scripts')

  <script type="text/javascript">
      var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $projectsChart = $('#projects-chart')
  // eslint-disable-next-line no-unused-vars
  var projectsChart = new Chart($projectsChart, {
    type: 'bar',
    data: {
      labels: ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: [1000, 2000, 3000, 2500, 2700, 2500, 3000]
        },
        {
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: [700, 1700, 2700, 2000, 1800, 1500, 2000]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
  </script>

   <script src="{{ asset('Admin/dist/js/pages/dashboard3.js') }}"></script>
@endpush