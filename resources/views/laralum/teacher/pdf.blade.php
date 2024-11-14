<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application for Opening Teacher Profile</title>
    <style>
        body {
            margin: auto;
            width: 50%;
            border: 1px solid #D1D5DB;
            padding: 10px;
        }

        .main-header {
            font-size: 20px;
            font-weight: 700;
        }

        .header {
            font-weight: 600
        }

        .flex {
            margin: 2%
        }

        .field {
            float: left;
            width: 30%;
            font-weight: 600
        }

        .box1 {
            border: 1px solid #E5E7EB;
            margin-top: 1%;
        }

        hr {
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
        }

        .signature {
            width: 30%;
            margin-top: 10%;
        }

        .img {
            height: 200px;
            width: 200px;
            padding: 0.25rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
    </style>
</head>

<body>

    {{-- <div style="height: 200px">
        <div style="width: 30%; float: left"><img class="img"
                src="{{asset("storage/image/teacher/$teacher->image_url")}}" />
    </div>

    <div style="width: 50%; float: left; text-align: center">
        <div class="main-header">Jahangirnagar University</div>
        <div>Savar, Dhaka</div>

        <p></p>
        <div class="header">Basic Information</div>
        <div>(Teachers)</div>
    </div>
    </div> --}}

    <div style="text-align: center">
        <div class="main-header">Jahangirnagar University</div>
        <div>Savar, Dhaka</div>

        <p></p>
        <div class="header">Basic Information</div>
        <div>(Teachers)</div>
    </div>

    <p></p>
    <div style="margin-top:5%" class="box1">

        <div class="flex">
            <div class="field">Name</div>
            <div class="value">: {{$teacher->name}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Employee ID</div>
            <div class="value">: {{$teacher->employee_id}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Designation</div>
            <div class="value">: {{optional($teacher->designationInfo)->name}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Department / Office</div>
            <div class="value">: {{optional($teacher->department)->name}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Phone Number</div>
            <div class="value">: {{$teacher->cell_phone}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Email</div>
            <div class="value">: {{$teacher->email}}</div>
        </div>
        <hr>

        <div class="flex">
            <div class="field">Research Interest</div>
            <div class="value">: {{ strip_tags($teacher->research_interest)}}</div>
        </div>

    </div>

    <div class="signature">
        <hr>
        <div>Signature of applicant</div>
        <div>Date :</div>
    </div>

    <div style="text-align: center; margin-top:10%"><strong>For Office use only</strong></div>
    <div class="box1">
        <div class="flex">
            <div class="field">Account Created On</div>
            <div class="value">: {{$teacher->completed_at ? $teacher->completed_at->format('Y-m-d h:i:s a') : ''}}</div>
        </div>
        <hr>
        <div class="flex">
            <div class="field">Account Created By</div>
            <div class="value">: {{optional($teacher->activatedBy)->name}}</div>
        </div>
    </div>

</body>

</html>