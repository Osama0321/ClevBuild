@extends('layouts.admin.app', [ 'title' => 'Project Listing'])

<head>
    <!-- Include jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
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
            <!--h1 class="m-0">Sprinkler System Projects</h1-->
			
          </div>
		  <div class="col-sm-6">
              <div class="text-right btn-wrapper">
                <a href="{{route('projects.add')}}" class="btn btn-primary d-inline-flex align-items-center">Add New Project</a>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @include('message')
        <!-- Small boxes (Stat box) -->
        <div class="row contentBoxesWrap">
          @forelse($projects as $project)  
            <div class="col-lg-4">
				<a href="{{ route('floors', ['project_id' => $project->project_id]) }}" title="View Floors">
					<div class="bg-white rounded-lg contentBox mb-3">
					<div class="d-flex align-items-start mb-3 justify-content-between">
						<div>
						<h3>{{ $project->project_name }}</h3>
						<p>{{ $project->category->category_name }}</p>
						</div>
						<div class="Badge"><span id = "complete_percent_{{ $project->project_id}}"></span>% Complete</div>
					</div>
					<ul>
						<li>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin "><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
						<span>{{ $project->address }}</span>
						</li>
						<li>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 "><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
						<span id="pipe_count_{{ $project->project_id}}"></span>
						</li>
						<li>
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock "><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
						<span>Updated {{ $project->updated_at }}</span>
						</li>
					</ul>
					<div class="mt-3">
						<div class="progressBar">
							<div id="progress-bar_{{ $project->project_id}}" class="progressBarInner" style="width: 38%;"></div>
						</div>
					</div>
					</div>
				</a>
            </div>
          @empty
            <div class="col-lg-4">
                <div class="bg-white rounded-lg contentBox mb-3 text-center">
                    <h3>No Project Available</h3>
                </div>
            </div>
          @endforelse  
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<!-- ./wrapper -->
@endsection

<script>

// Function to calculate completed percentage and count pipe tasks for each project
function calculateProjectCompletion(project) {
	let totalProjectLength = 0;
	let totalCompletedLength = 0;
	let totalFloors = 0;
	let totalFloorPercent = 0;
	let totalPipeTasks = 0;

	// Loop through each floor in the project
	project.floors.forEach(floor => {
	let totalFloorLength = 0;
	let completedFloorLength = 0;
	let pipeTaskCount = 0;

	// Loop through each task in the floor
	floor.tasks.forEach(task => {
	  if (task.task_type === 'pipe') {
		totalFloorLength += task.length_in_inches;
		pipeTaskCount++;

		// If task is completed (task_status_id === 4)
		if (task.task_status_id === 4) {
		  completedFloorLength += task.length_in_inches;
		}
	  }
	});

	// Calculate floor completion percentage
	const floorCompletionPercentage = totalFloorLength > 0 ? (completedFloorLength / totalFloorLength) * 100 : 0;

	// Add floor data to the project totals
	totalProjectLength += totalFloorLength;
	totalCompletedLength += completedFloorLength;
	totalFloors++;

	// Add floor completion percentage to the average calculation
	totalFloorPercent += floorCompletionPercentage;

	// Format the completed length for the floor in feet and inches
	const floorCompletedFeet = Math.floor(completedFloorLength / 12);
	const floorCompletedInches = completedFloorLength % 12;
	const floorCompletedLengthFormatted = `${floorCompletedFeet}'-${floorCompletedInches}"`;

	});

	// Calculate total project completion percentage
	const projectCompletionPercentage = totalProjectLength > 0 ? (totalCompletedLength / totalProjectLength) * 100 : 0;

	// Calculate average completion percentage for the project
	const averageCompletionPercentage = totalFloors > 0 ? totalFloorPercent / totalFloors : 0;

	// Format total completed length for the project in feet and inches
	const projectCompletedFeet = Math.floor(totalCompletedLength / 12);
	const projectCompletedInches = totalCompletedLength % 12;
	const projectCompletedLengthFormatted = `${projectCompletedFeet}'-${projectCompletedInches}"`;

	// Log total pipe tasks count for the project
	let totalPipeTasksInProject = 0;
	project.floors.forEach(floor => {
		floor.tasks.forEach(task => {
			if (task.task_type === 'pipe') {
				totalPipeTasksInProject++;
			}
		});
	});
	 const progressBar = document.getElementById('progress-bar_'+project.project_id);
	     progressBar.style.width = `${Math.round(averageCompletionPercentage)}%`;
    progressBar.setAttribute('aria-valuenow', `${Math.round(averageCompletionPercentage)}`);

	$('#pipe_count_'+project.project_id).html(`${totalPipeTasksInProject}`);
	$('#complete_percent_'+project.project_id).html(`${Math.round(averageCompletionPercentage)}`);
	//$('#progress-bar'+project.project_id).html(`${Math.round(averageCompletionPercentage)}`);
}


document.addEventListener("DOMContentLoaded", () => {
	let projects = {!! json_encode($projects) !!};
	// Loop through each project and calculate completion data
	projects.forEach(project => {
	  calculateProjectCompletion(project);
	});
});

</script>