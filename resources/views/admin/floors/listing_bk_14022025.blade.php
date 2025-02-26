@extends('layouts.admin.app', [ 'title' => 'floor Listing'])


@section('content')

<style>
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
</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1 class="m-0">Sprinkler System floors</h1> -->
          </div>
          <div class="col-sm-6">
              <div class="text-right btn-wrapper">
                <a href="{{route('floors.add')}}" class="btn btn-primary d-inline-flex align-items-center">Add New Floor</a>
              </div>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row contentBoxesWrap">
          @forelse($floors as $floor)
            <div class="col-lg-4">
				<div class="bg-white rounded-lg contentBox mb-3">
					<a href="{{ route('cadeditorNew', ['floor_id' => $floor->floor_id]) }}" target="_blank" title="View In Cadviewer">
                        <div class="d-flex align-items-start mb-3 justify-content-between">
                            <div>
                            <h3>{{ $floor->floor_name }}</h3>
                            <p>{{ $floor->category->category_name }}</p>
                            </div>
                            <div class="Badge">
                              @php
                                $pipeTasks = array_filter($floor->tasks->toArray(), function($task) {
                                    return isset($task['task_type']) && $task['task_type'] === 'pipe';
                                });
                                $pipeTaskCount = count($pipeTasks);
                                // Calculate total length of all pipe tasks
                                $totalPipeLength = array_reduce($pipeTasks, function($carry, $task) {
                                  return $carry + ($task['length_in_inches'] ?? 0); 
                                }, 0);

                                // Calculate total length of completed pipe tasks
                                $completedPipeLength = array_reduce($pipeTasks, function($carry, $task) {
                                  return $carry + (isset($task['status']['status_name'], $task['length_in_inches']) && $task['status']['status_name'] === 'Completed' ? $task['length_in_inches'] : 0);
                                }, 0);

                                // Calculate the completion percentage based on length
                                $completionPercentage = $totalPipeLength > 0 ? round(($completedPipeLength / $totalPipeLength) * 100) : 0;

                              @endphp
                              {{ $completionPercentage }}% Complete
                            </div>
                        </div>
					</a> 
					<div style="float:right;">
						<div class="mb-1" style="text-align: end;" title="View Tasks">
							<a href="{{ route('tasks', ['floor_id' => $floor->floor_id]) }}" class="btn btn-primary">Tasks</a>
						</div>
						<div class="mb-1" style="text-align: end;" title="View In Cadviewer">
							<a href="{{ route('cadeditorNew', ['floor_id' => $floor->floor_id]) }}" target="_blank" class="btn btn-primary">View In Cadviewer</a>
						</div>						
					</div>

					<a href="{{ route('cadeditorNew', ['floor_id' => $floor->floor_id]) }}" target="_blank" title="View In Cadviewer">
                        <ul>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin "><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span>{{ $floor->address }}</span>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 "><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
                            <span>
                                {{ $pipeTaskCount }}
                            </span>
                            </li>
                            <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock "><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span>Updated {{ $floor->updated_at }}</span>
                            </li>
                        </ul>
                        <div class="mt-3">
                            <div class="progressBar">
                                <div class="progressBarInner" style="width: {{ $completionPercentage }}%;"></div>
                            </div>
                        </div>
					</a> 
				</div>
            </div>
          @empty
            <div class="col-lg-4">
                <div class="bg-white rounded-lg contentBox mb-3 text-center">
                    <h3>No Floor Available</h3>
                </div>
            </div>
          @endforelse 
        </div>
      </div>
    </section>
  </div>
@endsection