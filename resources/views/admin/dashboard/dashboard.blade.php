
@extends('layouts.admin.app', [ 'title' => 'Dashboard'])
@push('styles')
  <style>
    body {
        font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    html, body {
        height: 100%;
        overflow: hidden;
    }

    .text-orange {
        color: #ea580c;
    }

    .text-sm {
        font-size: .875rem;
        line-height: 1.25rem;
    }

    .font-medium {
        font-weight: 500;
    }

    .text-gray-500 {
        color: #6b7280;
    }

    .text-gray-600 {
        color: #4b5563;
    }

    .text-gray-700 {
        color: #374151;
    }

    .text-gray-800 {
        color: #1f2937;
    }

    .text-lg {
        font-size: 1.125rem;
        line-height: 1.75rem;
    }

    .bg-grey {
        background-color: #f9fafb;
    }

    .rounded-lg {
        border-radius: .5rem;
    }

    .text-2xl {
        font-size: 1.5rem;
        line-height: 2rem;
    }

    .font-bold {
        font-weight: 700;
    }

    .text-blue {
        color: #2563eb;
    }

    .text-xs {
        font-size: .75rem;
        line-height: 1rem;
    }

    .text-green-600 {
        color: #16a34a;
    }

    .dashboard-header {
        padding-inline: 32px;
        box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;
    }

    .dashboard-header h1 {
        margin-left: 16px;
        font-size: 27px;
        font-weight: 700;
        margin-bottom: 0;
    }

    .dashboard-header svg {
        stroke: rgb(37, 99, 235);
        width: 32px;
        height: 32px;
    }

    main .main-content {
        padding: 32px;
        display: flex;
        gap: 32px;
        background-color: #f3f4f6;
    }

    main .main-content .main-content-inner {
        flex: 1;
        max-height: calc(100vh - 12rem);
        overflow: auto;
    }

    main .main-content .main-content-inner:first-child {
        padding-right: 15px;
    }

    main .main-content .main-content-inner:last-child {
        padding-left: 15px;
    }

    .main-content-inner .main-content-box {
        padding: 24px;
        background-color: #fff;
        box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.1) 0px 4px 6px -4px;
        border-radius: 12px;
    }

    .main-content-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
    }

    .main-content-header h2 {
        margin: 0;
        font-size: 20px;
        line-height: 28px;
    }

    .main-content-header svg {
        stroke: rgb(37, 99, 235);
    }

    .card-link-header {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .card-link-header h2 {
        font-size: 20px;
        line-height: 28px;
        margin: 0;
        color: rgb(31, 41, 55);
        flex: 1;
    }

    .card-link-header svg {
        stroke: rgb(156, 163, 175);
        transition: .2s;
    }

    .card-link-header button {
        border: 0;
        background: none;
        padding: 8px;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        transform: rotate(180deg);
    }

    .card-link-header button svg {
        width: 20px;
        height: 20px;
        stroke: rgb(156, 163, 175) !important;
    }

    .card-link-header button:hover {
        background-color: #f3f4f6;
    }

    .card .card-header {
        background: none;
        border: 0;
        padding: 0;
        padding-bottom: 0;
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .card .card-header a.card-link {
        flex: 1;
    }

    .main-content-inner .main-content-box .card {
        border: 0;
        border-radius: 8px;
        margin-bottom: 24px;
        box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.1) 0px 2px 4px -2px;
    }

    .main-content-inner .main-content-box .card .card-spacing {
        padding: 24px;
    }

    .card .card-body {
        padding: 24px;
        background-color: #f7faff;
        border-top: 1px solid #e5e7eb;
    }

    .card .card-header:hover .card-link-header svg {
        stroke: rgb(37, 99, 235);
    }

    .card .card-header a.card-link[aria-expanded="true"] .card-link-header button {
        background-color: #eff6ff;
        transform: none;
    }

    .card .card-header a.card-link[aria-expanded="true"] .card-link-header button svg {
        stroke: rgb(37, 99, 235) !important;
    }

    .weatherBox button {
        color: #15803d;
        padding: 8px;
        font-weight: 500;
        border: 0;
        background: none;
    }

    .weatherBox button span.text-sm {
        font-size: 14px;
        margin-left: 8px;
    }

    .weatherBox {
        border: 1px solid rgb(187, 247, 208);
        background-color: rgb(240, 253, 244);
        border-radius: 8px;
    }

    .weatherBox .wheather-content {
        transition: none;
        padding: 16px;
        padding-top: 8px;
    }

    .weatherBox .wheather-content .weather-content-inline {
        margin-bottom: 12px;
        gap: 12px;
        color: #15803d;
    }

    .weatherBox .wheather-content .weather-content-inline span.text-sm {
        margin-left: 8px;
    }

    .weatherBox button[aria-expanded="true"] {
        width: 100%;
        justify-content: space-between;
        border-bottom: 1px solid rgb(229, 231, 235);
    }

    .progress-bg {
        height: 10px;
        background-color: rgb(229, 231, 235);
        border-radius: 50px;
    }

    .progress-bg .bg-blue {
        background-color: rgb(96, 165, 250);
        height: 10px;
        border-radius: 10px;
    }

    .progressBar span.text-xs {
        font-size: 12px;
        color: rgb(75, 85, 99);
    }

    .progress-bg .bg-green {
        height: 10px;
        border-radius: 10px;
        background-color: rgb(52, 211, 153);
    }

    .progress-bg .bg-yellow {
        height: 10px;
        border-radius: 10px;
        background-color: rgb(251, 191, 36);
    }

    .progress-bg .bg-purple {
        height: 10px;
        border-radius: 10px;
        background-color: rgb(129, 140, 248);
    }

    .progress-bg .bg-blue-500 {
        height: 10px;
        border-radius: 10px;
        background-color: #3b82f6;
    }

    .progress-bg .bg-orange {
        background-color: #f97316;
        height: 10px;
        border-radius: 10px;
    }


    .floor-heading h4 {
        margin: 0;
        font-size: 18px;
        color: rgb(31, 41, 55);
        padding-left: 8px;
        position: relative;
        line-height: 28px;
    }

    .floor-heading h4::before {
        content: '';
        width: 1.99653px;
        height: 23.9931px;
        position: absolute;
        left: 0;
        background: rgb(37, 99, 235);
        bottom: 0;
    }

    .floor-detail .floor-detail-box {
        padding: 16px;
        border: 1px solid rgb(243, 244, 246);
        background-color: #fff;
        border-radius: 8px;
        box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;
        position: relative;
        overflow: hidden;
    }

    .floor-detail .floor-detail-box::before {
        content: '';
        width: 3.99306px;
        height: 100%;
        background-color: rgb(37, 99, 235);
        position: absolute;
        left: 0;
        top: 0;
        opacity: .25;
    }

    .floor-detail .floor-detail-box .seprator {
        flex: 1;
        height: 1px;
        background-color: rgb(229, 231, 235);
        margin-inline: 12px;
    }

    .floor-detail {
        display: flex;
        flex-direction: column;
        row-gap: 24px;
    }

    .floor-detail-box span.text-sm {
        font-size: 14px;
        font-weight: 600;
    }

    .floor-detail-box span.text-xs {
        font-size: 12px;
        color: rgb(107, 114, 128);
    }

    .floor-detail .floor-detail-box .progress-content {
        margin-top: 12px;
    }

    .Timeline-Box-Wrapper {
        display: flex;
        gap: 24px;
    }

    .Timeline-Box-Wrapper .globalbox {
        flex: 1;
    }

    .blue-box-bg {
        padding: 16px;
        background-color: rgb(239, 246, 255);
        border-radius: 8px;
    }

    .orange-box-bg {
        padding: 16px;
        border-radius: 8px;
        background-color: rgb(255, 247, 237);
    }

    .project-timeline-detail {
        gap: 16px;
    }

    .project-timeline-detail .bg-grey {
        padding: 12px;
    }

    .project-timeline-detail .bg-grey p {
        margin: 0;
    }

    .globalbox p {
        margin: 0;
    }

    .tabsBtnWrapper button.btn {
        padding: 4px 12px;
        font-size: 14px;
        font-weight: 500;
        margin-left: 4px;
        color: rgb(75, 85, 99);
        border-radius: 6px;
        box-shadow: none;
    }

    .tabsBtnWrapper button.btn:first-child {
        margin-left: 8px;
    }

    .tabsBtnWrapper button.btn:hover {
        background-color: #f3f4f6;
    }

    .tabsBtnWrapper button.btn.active {
        background-color: #2563eb;
        color: #fff;
    }

    .PerformanceBoxes .performance-box-item {
        padding: 12px;
        background-color: rgb(249, 250, 251);
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .PerformanceBoxes .performance-box-item:last-child {
        margin-bottom: 0;
    }

    .progress-content-inline {
        display: flex;
        gap: 16px;
    }

    .card-header .dropdown button.btn {
        padding: 4px 8px;
        background-color: rgb(254, 242, 242);
        font-size: 16px;
        color: rgb(220, 38, 38);
        border: 0;
        line-height: 24px;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: none;
    }

    .card-header .dropdown button.btn:active {
        background-color: rgb(254, 242, 242);
        color: rgb(220, 38, 38);
    }

    .card-header .dropdown button.btn:focus {
        box-shadow: none !important;
    }

    .card-header .dropdown button.btn::after {
        display: none;
    }

    .card-header .dropdown .dropdown-menu {
        min-width: 280px;
        padding: 16px;
    }

    .card-header .dropdown .dropdown-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .card-header .dropdown .dropdown-menu ul li {
        margin-bottom: 8px;
        position: relative;
        padding-left: 15px;
        display: flex;
    }

    .card-header .dropdown .dropdown-menu ul li:last-child {
        margin: 0;
    }

    .card-header .dropdown .dropdown-menu ul li::before {
        content: '';
        position: absolute;
        left: 0;
        width: 5px;
        height: 5px;
        background: rgb(239, 68, 68);
        border-radius: 50px;
        top: 50%;
        transform: translateY(-50%);
    }
  </style>
@endpush

@section('content')
<section>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="dashbaord-page h-100">
          <div class="dashboard-header pt-3 pb-3">
              <div class="d-flex align-items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="lucide lucide-layout-dashboard h-8 w-8 text-blue-600">
                      <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                      <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                      <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                      <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                  </svg>
                  <h1>Construction Progress Dashboard</h1>
              </div>
          </div>
          <main class="h-100">
              <div class="main-content h-100">
                  <div class="main-content-inner">
                      <div class="main-content-box">
                          <div class="main-content-header">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                                  <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                                  <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                                  <path d="M10 6h4"></path>
                                  <path d="M10 10h4"></path>
                                  <path d="M10 14h4"></path>
                                  <path d="M10 18h4"></path>
                              </svg>
                              <h2>Building Progress</h2>
                          </div>
                          <div id="accordion">
                              <div class="card">
                                  <div class="card-spacing">
                                      <div class="card-header">
                                          <a class="card-link" data-toggle="collapse" href="#collapseOne">
                                              <div class="card-link-header">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                      viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                      <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                                                      <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                                                      <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                                                      <path d="M10 6h4"></path>
                                                      <path d="M10 10h4"></path>
                                                      <path d="M10 14h4"></path>
                                                      <path d="M10 18h4"></path>
                                                  </svg>
                                                  <h2>742 Maple Avenue, Brooklyn, NY 11201</h2>
                                                  <button><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                          viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                          <path d="m18 15-6-6-6 6"></path>
                                                      </svg></button>
                                              </div>
                                          </a>
                                          <div class="dropdown">
                                              <button class="btn btn-danger dropdown-toggle" type="button"
                                                  data-toggle="dropdown" aria-expanded="false">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                      viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                      <circle cx="12" cy="12" r="10"></circle>
                                                      <line x1="12" x2="12" y1="8" y2="12"></line>
                                                      <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                                  </svg>
                                                  <span class="text-sm font-medium">3</span>
                                              </button>
                                              <div class="dropdown-menu">
                                                  <h4 class="text-sm font-medium text-gray-700 mb-2">Issues Detected</h4>
                                                  <ul>
                                                      <li class="text-sm text-gray-600">Water pressure inconsistency on Floor 2</li>
                                                      <li class="text-sm text-gray-600">Sprinkler head misalignment in east wing</li>
                                                      <li class="text-sm text-gray-600">Pipe joint leak detected in utility room</li>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="mt-4 d-flex align-items-center">
                                          <div class="weatherBox">
                                              <button type="button" class="d-flex align-items-center"
                                                  data-toggle="collapse" href="#weatherBoxAccordion">
                                                  <div class="d-flex align-items-center">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                          viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                          <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z">
                                                          </path>
                                                      </svg>
                                                      <span class="text-sm font-medium">72°F</span>
                                                  </div>
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                      viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                      <path d="m6 9 6 6 6-6"></path>
                                                  </svg>
                                              </button>
                                              <div id="weatherBoxAccordion" class="collapse wheather-content">
                                                  <div class="d-flex align-items-center weather-content-inline">
                                                      <div class="d-flex align-items-center">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                              viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                              stroke-width="2" stroke-linecap="round"
                                                              stroke-linejoin="round">
                                                              <path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"></path>
                                                          </svg>
                                                          <span class="text-sm">72°F</span>
                                                      </div>
                                                      <div class="d-flex align-items-center">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                              viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                              stroke-width="2" stroke-linecap="round"
                                                              stroke-linejoin="round">
                                                              <path d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"></path>
                                                              <path d="M9.6 4.6A2 2 0 1 1 11 8H2"></path>
                                                              <path d="M12.6 19.4A2 2 0 1 0 14 16H2"></path>
                                                          </svg>
                                                          <span class="text-sm">8 mph</span>
                                                      </div>
                                                      <div class="d-flex align-items-center">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                              viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                              stroke-width="2" stroke-linecap="round"
                                                              stroke-linejoin="round">
                                                              <path
                                                                  d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z">
                                                              </path>
                                                              <path
                                                                  d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97">
                                                              </path>
                                                          </svg>
                                                          <span class="text-sm">65%</span>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="mt-4">
                                          <div class="progress-content">
                                              <div class="progressBar">
                                                  <div class="d-flex align-items-center justify-content-between mb-1">
                                                      <span class="text-xs">Piping</span>
                                                      <span class="text-xs">80%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-blue" style="width: 80%;"></div>
                                                  </div>
                                              </div>
                                              <div class="progressBar">
                                                  <div
                                                      class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                      <span class="text-xs">Sprinklers Installed</span>
                                                      <span class="text-xs">75%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-green" style="width: 75%;"></div>
                                                  </div>
                                              </div>
                                              <div class="progressBar">
                                                  <div
                                                      class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                      <span class="text-xs">Sprinklers Secured</span>
                                                      <span class="text-xs">70%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-yellow" style="width: 70%;"></div>
                                                  </div>
                                              </div>
                                              <div class="progressBar">
                                                  <div
                                                      class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                      <span class="text-xs">Sprinklers Covered</span>
                                                      <span class="text-xs">65%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-purple" style="width: 65%;"></div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div id="collapseOne" class="collapse" data-parent="#accordion">
                                      <div class="card-body">
                                          <div class="floor-detail">
                                              <div class="floor-heading">
                                                  <h4>
                                                      Floor Details
                                                  </h4>
                                              </div>
                                              <div class="floor-detail-box">
                                                  <div class=" d-flex align-items-center">
                                                      <span class="text-sm">Floor 1</span>
                                                      <div class="seprator"></div>
                                                      <span class="text-xs">Level 1</span>
                                                  </div>
                                                  <div class="progress-content">
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1">
                                                              <span class="text-xs">Piping</span>
                                                              <span class="text-xs">90%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-blue" style="width: 90%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Installed</span>
                                                              <span class="text-xs">85%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-green" style="width: 85%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Secured</span>
                                                              <span class="text-xs">80%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-yellow" style="width: 80%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Covered</span>
                                                              <span class="text-xs">75%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-purple" style="width: 75%;"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="floor-detail-box">
                                                  <div class=" d-flex align-items-center">
                                                      <span class="text-sm">Floor 2</span>
                                                      <div class="seprator"></div>
                                                      <span class="text-xs">Level 2</span>
                                                  </div>
                                                  <div class="progress-content">
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1">
                                                              <span class="text-xs">Piping</span>
                                                              <span class="text-xs">90%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-blue" style="width: 90%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Installed</span>
                                                              <span class="text-xs">85%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-green" style="width: 85%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Secured</span>
                                                              <span class="text-xs">80%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-yellow" style="width: 80%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Covered</span>
                                                              <span class="text-xs">75%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-purple" style="width: 75%;"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="floor-detail-box">
                                                  <div class=" d-flex align-items-center">
                                                      <span class="text-sm">Floor 3</span>
                                                      <div class="seprator"></div>
                                                      <span class="text-xs">Level 3</span>
                                                  </div>
                                                  <div class="progress-content">
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1">
                                                              <span class="text-xs">Piping</span>
                                                              <span class="text-xs">90%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-blue" style="width: 90%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Installed</span>
                                                              <span class="text-xs">85%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-green" style="width: 85%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Secured</span>
                                                              <span class="text-xs">80%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-yellow" style="width: 80%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Covered</span>
                                                              <span class="text-xs">75%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-purple" style="width: 75%;"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="card">
                                  <div class="card-spacing">
                                      <div class="card-header">
                                          <a class="card-link" data-toggle="collapse" href="#collapseTwo">
                                              <div class="card-link-header">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                      viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                      <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                                                      <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                                                      <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                                                      <path d="M10 6h4"></path>
                                                      <path d="M10 10h4"></path>
                                                      <path d="M10 14h4"></path>
                                                      <path d="M10 18h4"></path>
                                                  </svg>
                                                  <h2>742 Maple Avenue, Brooklyn, NY 11201</h2>
                                                  <button><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                          viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                          <path d="m18 15-6-6-6 6"></path>
                                                      </svg></button>
                                              </div>
                                          </a>
                                      </div>
                                      <div class="mt-4 d-flex align-items-center">
                                          <div class="weatherBox">
                                              <button type="button" class="d-flex align-items-center"
                                                  data-toggle="collapse" href="#weatherBoxAccordiontwo">
                                                  <div class="d-flex align-items-center">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                          viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                          <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z">
                                                          </path>
                                                      </svg>
                                                      <span class="text-sm font-medium">72°F</span>
                                                  </div>
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                      viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                      <path d="m6 9 6 6 6-6"></path>
                                                  </svg>
                                              </button>
                                              <div id="weatherBoxAccordiontwo" class="collapse wheather-content">
                                                  <div class="d-flex align-items-center weather-content-inline">
                                                      <div class="d-flex align-items-center">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                              viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                              stroke-width="2" stroke-linecap="round"
                                                              stroke-linejoin="round">
                                                              <path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"></path>
                                                          </svg>
                                                          <span class="text-sm">72°F</span>
                                                      </div>
                                                      <div class="d-flex align-items-center">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                              viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                              stroke-width="2" stroke-linecap="round"
                                                              stroke-linejoin="round">
                                                              <path d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"></path>
                                                              <path d="M9.6 4.6A2 2 0 1 1 11 8H2"></path>
                                                              <path d="M12.6 19.4A2 2 0 1 0 14 16H2"></path>
                                                          </svg>
                                                          <span class="text-sm">8 mph</span>
                                                      </div>
                                                      <div class="d-flex align-items-center">
                                                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                              viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                              stroke-width="2" stroke-linecap="round"
                                                              stroke-linejoin="round">
                                                              <path
                                                                  d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z">
                                                              </path>
                                                              <path
                                                                  d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97">
                                                              </path>
                                                          </svg>
                                                          <span class="text-sm">65%</span>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="mt-4">
                                          <div class="progress-content">
                                              <div class="progressBar">
                                                  <div class="d-flex align-items-center justify-content-between mb-1">
                                                      <span class="text-xs">Piping</span>
                                                      <span class="text-xs">80%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-blue" style="width: 80%;"></div>
                                                  </div>
                                              </div>
                                              <div class="progressBar">
                                                  <div
                                                      class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                      <span class="text-xs">Sprinklers Installed</span>
                                                      <span class="text-xs">75%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-green" style="width: 75%;"></div>
                                                  </div>
                                              </div>
                                              <div class="progressBar">
                                                  <div
                                                      class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                      <span class="text-xs">Sprinklers Secured</span>
                                                      <span class="text-xs">70%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-yellow" style="width: 70%;"></div>
                                                  </div>
                                              </div>
                                              <div class="progressBar">
                                                  <div
                                                      class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                      <span class="text-xs">Sprinklers Covered</span>
                                                      <span class="text-xs">65%</span>
                                                  </div>
                                                  <div class="progress-bg">
                                                      <div class="bg-purple" style="width: 65%;"></div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                      <div class="card-body">
                                          <div class="floor-detail">
                                              <div class="floor-heading">
                                                  <h4>
                                                      Floor Details
                                                  </h4>
                                              </div>
                                              <div class="floor-detail-box">
                                                  <div class=" d-flex align-items-center">
                                                      <span class="text-sm">Floor 1</span>
                                                      <div class="seprator"></div>
                                                      <span class="text-xs">Level 1</span>
                                                  </div>
                                                  <div class="progress-content">
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1">
                                                              <span class="text-xs">Piping</span>
                                                              <span class="text-xs">90%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-blue" style="width: 90%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Installed</span>
                                                              <span class="text-xs">85%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-green" style="width: 85%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Secured</span>
                                                              <span class="text-xs">80%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-yellow" style="width: 80%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Covered</span>
                                                              <span class="text-xs">75%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-purple" style="width: 75%;"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="floor-detail-box">
                                                  <div class=" d-flex align-items-center">
                                                      <span class="text-sm">Floor 2</span>
                                                      <div class="seprator"></div>
                                                      <span class="text-xs">Level 2</span>
                                                  </div>
                                                  <div class="progress-content">
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1">
                                                              <span class="text-xs">Piping</span>
                                                              <span class="text-xs">90%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-blue" style="width: 90%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Installed</span>
                                                              <span class="text-xs">85%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-green" style="width: 85%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Secured</span>
                                                              <span class="text-xs">80%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-yellow" style="width: 80%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Covered</span>
                                                              <span class="text-xs">75%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-purple" style="width: 75%;"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="floor-detail-box">
                                                  <div class=" d-flex align-items-center">
                                                      <span class="text-sm">Floor 3</span>
                                                      <div class="seprator"></div>
                                                      <span class="text-xs">Level 3</span>
                                                  </div>
                                                  <div class="progress-content">
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1">
                                                              <span class="text-xs">Piping</span>
                                                              <span class="text-xs">90%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-blue" style="width: 90%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Installed</span>
                                                              <span class="text-xs">85%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-green" style="width: 85%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Secured</span>
                                                              <span class="text-xs">80%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-yellow" style="width: 80%;"></div>
                                                          </div>
                                                      </div>
                                                      <div class="progressBar">
                                                          <div
                                                              class="d-flex align-items-center justify-content-between mb-1 mt-2">
                                                              <span class="text-xs">Covered</span>
                                                              <span class="text-xs">75%</span>
                                                          </div>
                                                          <div class="progress-bg">
                                                              <div class="bg-purple" style="width: 75%;"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="main-content-inner">
                      <div class="main-content-box">
                          <div class="main-content-header">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <line x1="10" x2="14" y1="2" y2="2"></line>
                                  <line x1="12" x2="15" y1="14" y2="11"></line>
                                  <circle cx="12" cy="14" r="8"></circle>
                              </svg>
                              <h2>Timeline Progress</h2>
                          </div>
                          <div class="Timeline-Box-Wrapper mb-4">
                              <div class="blue-box-bg globalbox">
                                  <h3 class="text-sm font-medium text-gray-600 mb-3">Sprinkler Installation Progress</h3>
                                  <div>
                                      <div class="d-flex justify-content-between align-items-center">
                                          <span class="text-sm text-gray-600">Total Sprinklers</span>
                                          <span class="text-sm font-medium text-gray-800">300</span>
                                      </div>
                                      <div class="d-flex justify-content-between align-items-center mt-2">
                                          <span class="text-sm text-gray-600">Installed</span>
                                          <span class="text-sm font-medium text-gray-800">225</span>
                                      </div>
                                      <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                                          <span class="text-sm text-gray-600">Remaining</span>
                                          <span class="text-sm font-medium text-gray-800">75</span>
                                      </div>
                                      <div class="progress-bg">
                                          <div class="bg-blue" style="width: 75%;"></div>
                                      </div>
                                  </div>
                              </div>
                              <div class="orange-box-bg globalbox">
                                  <h3 class="text-sm font-medium text-gray-600 mb-3">Schedule Status</h3>
                                  <div class="d-flex align-items-center warning-text mb-2">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                          stroke-linejoin="round" class="text-orange">
                                          <path
                                              d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z">
                                          </path>
                                          <path d="M12 9v4"></path>
                                          <path d="M12 17h.01"></path>
                                      </svg>
                                      <span class="text-sm text-orange ml-2 font-medium">Behind Schedule</span>
                                  </div>
                                  <div class="d-flex align-items-center justify-content-between text-sm">
                                      <span class="text-gray-600">Progress Variance</span>
                                      <span class="font-medium text-orange">-300.0%</span>
                                  </div>
                              </div>
                          </div>
                          <div class="main-content-header">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round">
                                  <circle cx="12" cy="12" r="10"></circle>
                                  <polyline points="12 6 12 12 16 14"></polyline>
                              </svg>
                              <h2>Project Timeline</h2>
                          </div>
                          <div class="d-flex project-timeline-detail">
                              <div class="bg-grey flex-grow-1 rounded-lg">
                                  <span class="text-sm text-gray-600">Start Date</span>
                                  <p class="text-lg font-medium text-gray-800">Feb 16, 2025</p>
                              </div>
                              <div class="bg-grey flex-grow-1 rounded-lg">
                                  <span class="text-sm text-gray-600">Planned End Date</span>
                                  <p class="text-lg font-medium text-gray-800">Feb 24, 2025</p>
                              </div>
                              <div class="bg-grey flex-grow-1 rounded-lg">
                                  <span class="text-sm text-gray-600">Estimated Completion</span>
                                  <p class="text-lg font-medium text-gray-800">Mar 20, 2025</p>
                              </div>
                          </div>
                          <div class="d-flex project-timeline-detail mt-3">
                              <div class="d-flex bg-grey flex-grow-1 rounded-lg">
                                  <div class="flex-grow-1">
                                      <span class="text-sm text-gray-600">Days Needed</span>
                                      <p class="text-lg font-medium text-gray-800">8</p>
                                  </div>
                                  <div class="flex-grow-1">
                                      <span class="text-sm text-gray-600">Days Worked</span>
                                      <p class="text-lg font-medium text-gray-800">30</p>
                                  </div>
                                  <div class="flex-grow-1">
                                      <span class="text-sm text-gray-600">Days Remaining</span>
                                      <p class="text-lg font-medium text-gray-800">2</p>
                                  </div>
                                  <div class="flex-grow-1">
                                      <span class="text-sm text-gray-600">Daily Rate Needed</span>
                                      <p class="text-lg font-medium text-gray-800">38 sprinklers</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="main-content-box mt-5">
                          <div class="d-flex justify-content-between mb-4">
                              <div class="main-content-header mb-0">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round">
                                      <path d="M3 3v18h18"></path>
                                      <path d="M18 17V9"></path>
                                      <path d="M13 17V5"></path>
                                      <path d="M8 17v-3"></path>
                                  </svg>
                                  <h2>Company Analytics</h2>
                              </div>
                              <div class="d-flex align-items-center">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round" class="text-gray-500">
                                      <path d="M8 2v4"></path>
                                      <path d="M16 2v4"></path>
                                      <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                      <path d="M3 10h18"></path>
                                  </svg>
                                  <div class="d-flex align-items-center tabsBtnWrapper">
                                      <button type="button" class="btn active">Today</button>
                                      <button type="button" class="btn">This Week</button>
                                      <button type="button" class="btn">This Month</button>
                                      <button type="button" class="btn">This Year</button>
                                  </div>
                              </div>
                          </div>
                          <div class="Timeline-Box-Wrapper mb-4">
                              <div class="blue-box-bg globalbox">
                                  <h3 class="text-sm font-medium text-gray-600 mb-2">Total Sprinklers Installed</h3>
                                  <p class="text-2xl font-bold text-blue">246</p>
                                  <p class="text-xs text-gray-600 mt-1">Today</p>
                              </div>
                              <div class="orange-box-bg globalbox">
                                  <h3 class="text-sm font-medium text-gray-600 mb-2">Total Piping Installed</h3>
                                  <p class="text-2xl font-bold text-orange">2307 ft</p>
                                  <p class="text-xs text-gray-600 mt-1">Today</p>
                              </div>
                          </div>
                          <div class="performance-wrapper">
                              <div class="d-flex align-items-center mb-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round" class="text-gray-500">
                                      <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                                      <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                                      <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                                      <path d="M10 6h4"></path>
                                      <path d="M10 10h4"></path>
                                      <path d="M10 14h4"></path>
                                      <path d="M10 18h4"></path>
                                  </svg>
                                  <h3 class="text-sm font-medium text-gray-700 mb-0 ml-2">Top Building Performance</h3>
                              </div>
                              <div class="PerformanceBoxes">
                                  <div class="performance-box-item">
                                      <div class="d-flex align-items-center justify-content-between mb-2">
                                          <span class="text-sm font-medium text-gray-700">1. 742 Maple Avenue, Brooklyn,
                                              NY 11201</span>
                                          <span class="text-sm font-medium text-green-600">NaN% Complete</span>
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Sprinklers:</span>
                                              <span class="font-medium text-blue">NaN%</span>
                                          </div>
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Piping:</span>
                                              <span class="font-medium text-orange">80.0%</span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="performance-box-item">
                                      <div class="d-flex align-items-center justify-content-between mb-2">
                                          <span class="text-sm font-medium text-gray-700">2. 1580 Oak Street, Chicago, IL
                                              606011</span>
                                          <span class="text-sm font-medium text-green-600">NaN% Complete</span>
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Sprinklers:</span>
                                              <span class="font-medium text-blue">NaN%</span>
                                          </div>
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Piping:</span>
                                              <span class="font-medium text-orange">40.0%</span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="performance-box-item">
                                      <div class="d-flex align-items-center justify-content-between mb-2">
                                          <span class="text-sm font-medium text-gray-700">3. 350 Pine Boulevard, Seattle,
                                              WA 98101</span>
                                          <span class="text-sm font-medium text-green-600">NaN% Complete</span>
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Sprinklers:</span>
                                              <span class="font-medium text-blue">NaN%</span>
                                          </div>
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Piping:</span>
                                              <span class="font-medium text-orange">95.0%</span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="performance-wrapper mt-4">
                              <div class="d-flex align-items-center mb-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round" class="text-gray-500">
                                      <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                      <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                  </svg>
                                  <h3 class="text-sm font-medium text-gray-700 mb-0 ml-2">Top Worker Performance - Today
                                  </h3>
                              </div>
                              <div class="PerformanceBoxes">
                                  <div class="performance-box-item">
                                      <div class="d-flex align-items-center justify-content-between mb-2">
                                          <span class="text-sm font-medium text-gray-700">1. Faizan</span>
                                          <!-- <span class="text-sm font-medium text-green-600">NaN% Complete</span> -->
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Sprinklers:</span>
                                              <span class="font-medium text-blue">52</span>
                                          </div>
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Piping:</span>
                                              <span class="font-medium text-orange">527 ft</span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="performance-box-item">
                                      <div class="d-flex align-items-center justify-content-between mb-2">
                                          <span class="text-sm font-medium text-gray-700">2. Volvi</span>
                                          <!-- <span class="text-sm font-medium text-green-600">NaN% Complete</span> -->
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Sprinklers:</span>
                                              <span class="font-medium text-blue">54</span>
                                          </div>
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Piping:</span>
                                              <span class="font-medium text-orange">512 ft</span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="performance-box-item">
                                      <div class="d-flex align-items-center justify-content-between mb-2">
                                          <span class="text-sm font-medium text-gray-700">3. Tariq</span>
                                          <!-- <span class="text-sm font-medium text-green-600">NaN% Complete</span> -->
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Sprinklers:</span>
                                              <span class="font-medium text-blue">42</span>
                                          </div>
                                          <div class="flex-grow-1">
                                              <span class="text-gray-500">Piping:</span>
                                              <span class="font-medium text-orange">434 ft</span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="main-content-box mt-5">
                          <div class="d-flex justify-content-between mb-4">
                              <div class="main-content-header mb-0">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round" class="text-blue-600">
                                      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                      <circle cx="9" cy="7" r="4"></circle>
                                      <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                      <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                  </svg>
                                  <h2>Worker Progress</h2>
                              </div>
                              <div class="d-flex align-items-center">
                                  <div class="d-flex align-items-center tabsBtnWrapper">
                                      <button type="button" class="btn active">Today</button>
                                      <button type="button" class="btn">This Week</button>
                                      <button type="button" class="btn">This Month</button>
                                  </div>
                              </div>
                          </div>
                          <div class="progressContentWrapper">
                              <div class="progress-content-box">
                                  <h3 class="text-lg font-medium text-gray-800 mb-0">Motty Muller</h3>
                                  <div class="progress-content-inline mt-2">
                                      <div class="flex-grow-1">
                                          <div class="d-flex align-items-center justify-content-between mb-1">
                                              <span class="text-sm text-gray-600">Sprinklers Installed</span>
                                              <span class="text-sm font-medium text-gray-800">42 units</span>
                                          </div>
                                          <div class="progress-bg">
                                              <div class="bg-orange" style="width: 42%;"></div>
                                          </div>
                                      </div>
                                      <div class="flex-grow-1">
                                          <div class="d-flex align-items-center justify-content-between mb-1">
                                              <span class="text-sm text-gray-600">Piping Installed</span>
                                              <span class="text-sm font-medium text-gray-800">372 ft</span>
                                          </div>
                                          <div class="progress-bg">
                                              <div class="bg-blue-500" style="width: 37.2%;"></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="progress-content-box mt-3">
                                  <h3 class="text-lg font-medium text-gray-800 mb-0">Osama Burney</h3>
                                  <div class="progress-content-inline mt-2">
                                      <div class="flex-grow-1">
                                          <div class="d-flex align-items-center justify-content-between mb-1">
                                              <span class="text-sm text-gray-600">Sprinklers Installed</span>
                                              <span class="text-sm font-medium text-gray-800">60 units</span>
                                          </div>
                                          <div class="progress-bg">
                                              <div class="bg-orange" style="width: 60%;"></div>
                                          </div>
                                      </div>
                                      <div class="flex-grow-1">
                                          <div class="d-flex align-items-center justify-content-between mb-1">
                                              <span class="text-sm text-gray-600">Piping Installed</span>
                                              <span class="text-sm font-medium text-gray-800">535 ft</span>
                                          </div>
                                          <div class="progress-bg">
                                              <div class="bg-blue-500" style="width: 53.5%;"></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="progress-content-box mt-3">
                                  <h3 class="text-lg font-medium text-gray-800 mb-0">Tariq</h3>
                                  <div class="progress-content-inline mt-2">
                                      <div class="flex-grow-1">
                                          <div class="d-flex align-items-center justify-content-between mb-1">
                                              <span class="text-sm text-gray-600">Sprinklers Installed</span>
                                              <span class="text-sm font-medium text-gray-800">51 units</span>
                                          </div>
                                          <div class="progress-bg">
                                              <div class="bg-orange" style="width: 51%;"></div>
                                          </div>
                                      </div>
                                      <div class="flex-grow-1">
                                          <div class="d-flex align-items-center justify-content-between mb-1">
                                              <span class="text-sm text-gray-600">Piping Installed</span>
                                              <span class="text-sm font-medium text-gray-800">485 ft</span>
                                          </div>
                                          <div class="progress-bg">
                                              <div class="bg-blue-500" style="width: 48.5%;"></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </main>
        </div>
      </div>  
    </div>  
  </div>
</section>     
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