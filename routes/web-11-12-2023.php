<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
//    symlink('/home/devju/storage/app/public', '/home/devju/public_html/storage');
});

Route::group(['as' => 'Frontend::'], function () {
    /*
    |--------------------------------------------------------------------------
    | Website Front End Routes Here
    |--------------------------------------------------------------------------
    */

    Route::get('', 'Frontend\HomeController@index')->name('home');
    Route::get('/not-found', 'Frontend\HomeController@notFound')->name('not-found');
    Route::get('/something-wrong', 'Frontend\HomeController@somethingWrong')->name('something-wrong');

    Route::group(['prefix' => 'download', 'namespace' => 'Frontend', 'as' => 'download::'], function () {
        Route::get('', 'HomeController@downloadList')->name('list');
        Route::get('search', 'HomeController@fileSearch')->name('center::department::file::search');
    });

    Route::group(['prefix' => 'student-email', 'namespace' => 'Laralum', 'as' => 'student::'], function () {
        Route::get('email/username/generate', 'StudentEmailApplyController@generateUserName')->name('email::username:generate');
        Route::get('verify', 'StudentEmailApplyController@studentVerify')->name('verify');
        Route::get('apply', 'StudentEmailApplyController@apply')->name('email::apply');
        Route::post('apply', 'StudentEmailApplyController@store')->name('email::apply::submit');
    });

    Route::group(['prefix' => 'internet-connection/apply', 'namespace' => 'Laralum', 'as' => 'internet::'], function () {
        Route::get('', 'InternetConnectionController@applyView')->name('apply');
        Route::post('', 'InternetConnectionController@apply')->name('apply');
    });

    Route::group(['prefix' => 'internet-complain', 'namespace' => 'Laralum', 'as' => 'internet::'], function () {
        Route::get('', 'InternetComplainController@form')->name('complain');
        Route::post('', 'InternetComplainController@saveComplain')->name('complain');
    });

    Route::group(['prefix' => 'employee-email/apply', 'namespace' => 'Laralum', 'as' => 'employee::email::'], function () {
        Route::get('', 'EmployeeEmailController@applyView')->name('apply');
        Route::post('', 'EmployeeEmailController@apply')->name('apply');
    });

    Route::group(['prefix' => 'administration', 'namespace' => 'Laralum', 'as' => 'administration::'], function () {
        Route::get('', 'AdministrationController@administrationView')->name('view');
        Route::get('{admin}', 'AdministrationController@profileView')->name('profile');
    });

    Route::get('custom/slug/{slug}', 'Laralum\CustomPageController@view')->name('custom::slug::view');

    Route::get('misc/{slug}', 'Laralum\CustomPageController@view')->name('custom::slug::view');

    Route::group(['prefix' => 'certificate', 'namespace' => 'Laralum', 'as' => 'certificate::'], function () {
        Route::get('', 'LegalCertificateController@lists')->name('list');
        Route::get('search', 'LegalCertificateController@search')->name('search');
    });

    Route::group(['prefix' => 'journal', 'namespace' => 'Laralum', 'as' => 'journal::'], function () {
        Route::get('', 'JournalController@lists')->name('list');
        Route::get('search', 'JournalController@search')->name('search');
        Route::get('{content}/file', 'JournalController@contentDownload')->name('content::download');
    });

    Route::group(['prefix' => 'discussion', 'namespace' => 'Laralum', 'as' => 'event::'], function () {
        Route::get('', 'DepartmentController@discussionList')->name('list');
        Route::get('search', 'DepartmentController@discussionSearch')->name('search');
        Route::get('{discussion}', 'DepartmentController@discussionView')->name('view');
    });

    Route::get('academic-calendar', 'Laralum\ProgramController@calenderView')->name('calender::view');

    Route::group(['prefix' => 'gallery', 'namespace' => 'Laralum', 'as' => 'gallery::'], function () {
        Route::get('', 'SettingsController@galleryView')->name('view');
        Route::get('search', 'SettingsController@jsonSearch')->name('search');
    });

    Route::group(['prefix' => 'admission', 'namespace' => 'Laralum', 'as' => 'admission::'], function () {
        Route::get('{program}', 'ProgramController@view')->name('view');
    });

    Route::group(['prefix' => 'program', 'namespace' => 'Laralum', 'as' => 'program::'], function () {
        Route::get('', 'ProgramController@showList')->name('index');
        Route::get('search', 'ProgramController@search')->name('search');
        Route::get('{program}', 'ProgramController@view')->name('view');
    });

    Route::group(['prefix' => 'facility', 'namespace' => 'Laralum', 'as' => 'facility::'], function () {
        Route::get('', 'FacilityController@showList')->name('index');
        Route::get('{facility}', 'FacilityController@view')->name('view');
    });

    Route::group(['prefix' => 'research', 'namespace' => 'Laralum', 'as' => 'research::'], function () {
        Route::get('', 'ResearchController@showList')->name('index');
        Route::get('{research}', 'ResearchController@show')->name('view');
    });

    Route::group(['prefix' => 'faculty', 'namespace' => 'Laralum', 'as' => 'faculty::'], function () {
        Route::get('search', 'FacultyController@jsonSearch')->name('search');
        Route::get('{slug}', 'FacultyController@view')->name('view');
    });

    Route::group(['prefix' => 'institute', 'namespace' => 'Laralum', 'as' => 'institute::'], function () {
        Route::get('{slug}', 'DepartmentController@instituteView')->name('view');
        Route::get('{institute}/program/{program}', 'ProgramController@departmentView')->name('program::view');
    });

    Route::group(['prefix' => 'office', 'namespace' => 'Laralum', 'as' => 'office::'], function () {
        Route::get('search', 'CenterController@search')->name('search');
        Route::get('{slug}', 'CenterController@view')->name('view');
        Route::get('{center}/program/{program}', 'ProgramController@centerView')->name('program::view');
    });

    Route::group(['prefix' => 'center', 'namespace' => 'Laralum', 'as' => 'center::'], function () {
        Route::get('{slug}', 'CenterController@view')->name('view');
        Route::get('{center}/program/{program}', 'ProgramController@centerView')->name('program::view');
    });

    Route::group(['prefix' => 'hall', 'namespace' => 'Laralum', 'as' => 'hall::'], function () {
        Route::get('{slug}', 'HallController@view')->name('view');
    });

    Route::group(['prefix' => 'teachers', 'namespace' => 'Laralum', 'as' => 'teacher::'], function () {
        Route::get('', 'TeacherController@lists')->name('list');
        Route::get('apply', 'TeacherController@applyView')->name('apply');
        Route::post('apply', 'TeacherController@apply')->name('apply');
        Route::get('search', 'TeacherController@jsonSearch')->name('search');
        Route::get('{slug}', 'TeacherController@view')->name('view');
    });

    Route::group(['prefix' => 'officers', 'namespace' => 'Laralum', 'as' => 'officer::'], function () {
        Route::get('', 'OfficerController@lists')->name('list');
        Route::get('generate-slug', 'OfficerController@generateSlug');
        Route::get('search', 'OfficerController@search')->name('search');
        Route::get('{slug}', 'OfficerController@profile')->name('profile');
    });

    Route::group(['prefix' => 'department', 'namespace' => 'Laralum', 'as' => 'department::'], function () {
        Route::get('search', 'DepartmentController@jsonSearch')->name('search');
        Route::get('{slug}', 'DepartmentController@instituteView')->name('view');

        Route::group(['prefix' => '{department}'], function () {
            Route::get('program/{program}', 'ProgramController@departmentView')->name('program::view');
            Route::get('chairman-message', 'DepartmentController@chairmanMessageView')->name('chairman-message::view');
            Route::get('files', 'DepartmentController@fileLists')->name('file::list');
            Route::get('links', 'DepartmentController@linkListsByDepartment')->name('link::list');
            Route::get('research/{research}', 'ResearchController@view')->name('research::view');
            Route::get('facility/{facility}', 'FacilityController@departmentView')->name('facility::view');
        });
    });
});

// Auth Route
Auth::routes();

// File View Routes
Route::get('program/{program}/file', 'Laralum\ProgramController@fileView')->name('program::file::view');
Route::get('certificate/{certificate}/file', 'Laralum\LegalCertificateController@fileView')->name('certificate::file::view');
Route::get('file/{file}', 'Laralum\UploadedFileController@fileView')->name('file::view');
Route::get('center/{center}/file/{file}', 'Laralum\CenterController@fileView')->name('center::file::view');
Route::get('department/{department}/file/{file}', 'Laralum\DepartmentController@fileView')->name('department::file::view');
Route::get('discussion/{discussion}/file/{file}', 'Laralum\SettingsController@fileView')->name('event::file::view');

Route::group(['middleware' => ['auth', 'laralum.base', 'laralum.auth'], 'prefix' => 'admin', 'namespace' => 'Laralum', 'as' => 'Laralum::'], function () {
    Route::group(['prefix' => 'student', 'as' => 'student::'], function () {
        Route::get('', 'StudentController@index')->name('list');
        Route::get('upload', 'StudentController@uploadView')->name('upload-view');
        Route::post('upload', 'StudentController@upload')->name('upload');
        Route::get('create', 'StudentController@create')->name('create');
        Route::post('create', 'StudentController@store')->name('store');
        Route::get('{student}/edit', 'StudentController@edit')->name('edit');
        Route::post('{student}/edit', 'StudentController@update')->name('update');
        Route::get('{student}/delete', 'StudentController@destroy')->name('delete');
    });

    // Student Domain Email Apply
    Route::group(['prefix' => 'student-email-apply', 'as' => 'student-email-apply::'], function () {
        Route::get('', 'StudentEmailApplyController@index')->name('list');
        Route::get('export', 'StudentEmailApplyController@export')->name('export');
        Route::get('{email}/edit', 'StudentEmailApplyController@edit')->name('edit');
        Route::post('{email}/update', 'StudentEmailApplyController@update')->name('update');
        Route::get('{email}/mark-completed', 'StudentEmailApplyController@markCompleted')->name('mark::completed');
        Route::get('{email}/mark-pending', 'StudentEmailApplyController@markPending')->name('mark::pending');
        Route::get('{email}/mark-rejected', 'StudentEmailApplyController@markRejected')->name('mark::rejected');
        Route::get('{email}/mark-checked', 'StudentEmailApplyController@markChecked')->name('mark::checked');
        Route::get('{email}/detail', 'StudentEmailApplyController@detail')->name('detail');
        Route::get('{email}/delete', 'StudentEmailApplyController@destroy')->name('delete');
        Route::get('wrong-email', 'StudentEmailApplyController@findWrongEmail')->name('wrong-email');

        Route::group(['prefix' => 'admission-session', 'as' => 'admission-session::'], function () {
            Route::get('', 'StudentEmailApplyController@admissionSessionList')->name('list');
            Route::post('', 'StudentEmailApplyController@admissionSessionStore')->name('store');
            Route::get('{admissionSession}/edit', 'StudentEmailApplyController@admissionSessionEdit')->name('edit');
            Route::post('{admissionSession}/edit', 'StudentEmailApplyController@admissionSessionUpdate')->name('update');
            Route::get('{admissionSession}/delete', 'StudentEmailApplyController@admissionSessionDelete')->name('delete');
        });

        Route::group(['prefix' => 'program', 'as' => 'program::'], function () {
            Route::get('', 'StudentEmailApplyController@programList')->name('list');
            Route::post('', 'StudentEmailApplyController@programStore')->name('store');
            Route::get('{program}/edit', 'StudentEmailApplyController@programEdit')->name('edit');
            Route::post('{program}/edit', 'StudentEmailApplyController@programUpdate')->name('update');
            Route::get('{program}/delete', 'StudentEmailApplyController@programDelete')->name('delete');
        });

        // Route::resource('student-email-apply', 'Laralum\StudentEmailApplyController')->only('index', 'create', 'store');
    });

    // Center
    Route::group(['prefix' => 'center', 'as' => 'center::'], function () {
        Route::get('', 'CenterController@index')->name('list');
        Route::get('create', 'CenterController@create')->name('create');
        Route::post('create', 'CenterController@store')->name('store');
        Route::get('{center}/advance', 'CenterController@advanceOptionList')->name('advance');
        Route::get('{center}/edit', 'CenterController@edit')->name('edit');
        Route::post('{center}/edit', 'CenterController@update')->name('update');

        Route::get('{center}/assign', 'CenterController@assign')->name('assign');
        Route::post('{center}/assign', 'CenterController@assignSave')->name('assign::save');
        Route::get('{center}/unassign', 'CenterController@unassign')->name('unassign');

        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('', 'CenterController@typeList')->name('list');
            Route::post('create', 'CenterController@typeStore')->name('create');
            Route::get('{type}/edit', 'CenterController@typeEdit')->name('edit');
            Route::post('{type}/edit', 'CenterController@typeUpdate')->name('update');
        });

        Route::group(['prefix' => '{center}/upload', 'as' => 'upload::'], function () {
            Route::get('', 'CenterController@uploadList')->name('list');
            Route::get('create', 'CenterController@uploadCreate')->name('create');
            Route::post('create', 'CenterController@uploadStore')->name('store');
            Route::get('{upload}/delete', 'CenterController@uploadDelete')->name('delete');
            Route::get('{upload}/edit', 'CenterController@uploadEdit')->name('edit');
            Route::post('{upload}/edit', 'CenterController@uploadUpdate')->name('update');
        });

        Route::group(['prefix' => '{center}/officer', 'as' => 'officer::'], function () {
            Route::get('', 'OfficerController@departmentCenterOfficerList')->name('list');
            Route::get('create', 'OfficerController@create')->name('create');
        });

        Route::group(['prefix' => '{center}/program', 'as' => 'program::'], function () {
            Route::get('', 'ProgramController@departmentCenterHallList')->name('list');
            Route::get('create', 'ProgramController@createForCenterDepartmentHall')->name('create');
        });

        Route::group(['prefix' => '{center}/facility', 'as' => 'facility::'], function () {
            Route::get('', 'FacilityController@departmentCenterList')->name('list');
            Route::get('create', 'FacilityController@createForCenterDepartment')->name('create');
        });
    });

    // Internet Connection
    Route::group(['prefix' => 'internet-connection', 'as' => 'internet-connection::'], function () {
        Route::get('', 'InternetConnectionController@lists')->name('list');
        Route::get('{connection}/mark-rejected', 'InternetConnectionController@markRejected')->name('rejected');
        Route::get('{connection}/mark-completed', 'InternetConnectionController@markCompleted')->name('completed');
        Route::get('{connection}/delete', 'InternetConnectionController@delete')->name('delete');
        Route::get('{connection}/edit', 'InternetConnectionController@edit')->name('edit');
        Route::post('{connection}/edit', 'InternetConnectionController@update')->name('update');
        Route::get('{connection}/detail', 'InternetConnectionController@detail')->name('detail');
        Route::get('{email}/mark-completed-notify', 'InternetConnectionController@completedNotifyOnly')->name('completed-notify');
        Route::get('{connection}/pdf', 'InternetConnectionController@pdfDownload')->name('pdf');
        Route::get('export', 'InternetConnectionController@export')->name('export');
    });

    // Internet Complain
    Route::group(['prefix' => 'internet-complain', 'as' => 'internet-complain::'], function () {
        Route::get('', 'InternetComplainController@lists')->name('list');
        Route::post('create', 'InternetComplainController@save');
        Route::get('create', 'InternetComplainController@create')->name('create');
        Route::get('export', 'InternetComplainController@export')->name('export');
        Route::get('{complain}/detail', 'InternetComplainController@detail')->name('detail');
        Route::get('{complain}/pdf', 'InternetComplainController@pdfDownload')->name('pdf');
        Route::get('{complain}/edit', 'InternetComplainController@edit')->name('edit');
        Route::post('{complain}/edit', 'InternetComplainController@update')->name('update');
        Route::get('{complain}/assign-lineman', 'InternetComplainController@assignLineman')->name('assign-lineman');
        Route::get('{complain}/assign-team', 'InternetComplainController@assignTeam')->name('assign-team');
        Route::get('{complain}/mark-success', 'InternetComplainController@markSuccess')->name('success');
        Route::get('{complain}/mark-rejected', 'InternetComplainController@markRejected')->name('rejected');
        Route::get('{complain}/delete', 'InternetComplainController@delete')->name('delete');

        // Category
        Route::group(['prefix' => 'category', 'as' => 'category::'], function () {
            Route::get('', 'InternetComplainCategoryController@lists')->name('list');
            Route::post('create', 'InternetComplainCategoryController@store')->name('store');
            Route::get('{category}/edit', 'InternetComplainCategoryController@edit')->name('edit');
            Route::post('{category}/edit', 'InternetComplainCategoryController@update')->name('update');
            Route::get('{category}/delete', 'InternetComplainCategoryController@delete')->name('delete');
        });

        // User Type
        Route::group(['prefix' => 'user-type', 'as' => 'user-type::'], function () {
            Route::get('', 'InternetComplainUserTypeController@lists')->name('list');
            Route::post('create', 'InternetComplainUserTypeController@store')->name('store');
            Route::get('{type}/edit', 'InternetComplainUserTypeController@edit')->name('edit');
            Route::post('{type}/edit', 'InternetComplainUserTypeController@update')->name('update');
            Route::get('{type}/delete', 'InternetComplainUserTypeController@delete')->name('delete');
        });

        // team
        Route::group(['prefix' => 'team', 'as' => 'team::'], function () {
            Route::get('', 'InternetComplainTeamController@lists')->name('list');
            Route::post('create', 'InternetComplainTeamController@store')->name('store');
            Route::get('{team}/detach/{user}', 'InternetComplainTeamController@detachUserFromTeam')->name('detach');
            Route::get('{team}/edit', 'InternetComplainTeamController@edit')->name('edit');
            Route::post('{team}/edit', 'InternetComplainTeamController@update')->name('update');
            Route::get('{team}/delete', 'InternetComplainTeamController@delete')->name('delete');
        });
    });

    // Lineman
    Route::group(['prefix' => 'lineman', 'as' => 'lineman::'], function () {
        Route::get('', 'LinemanController@lists')->name('list');
        Route::post('create', 'LinemanController@store')->name('store');
        Route::get('{lineman}/edit', 'LinemanController@edit')->name('edit');
        Route::post('{lineman}/edit', 'LinemanController@update')->name('update');
        Route::get('{lineman}/delete', 'LinemanController@delete')->name('delete');
    });

    // Employee Email
    Route::group(['prefix' => 'employee-email', 'as' => 'employee-email::'], function () {
        Route::get('', 'EmployeeEmailController@lists')->name('list');
        Route::get('export', 'EmployeeEmailController@export')->name('export');
        Route::get('{email}/detail', 'EmployeeEmailController@detail')->name('detail');
        Route::get('{email}/pdf', 'EmployeeEmailController@pdfDownload')->name('pdf');
        Route::get('{email}/edit', 'EmployeeEmailController@edit')->name('edit');
        Route::post('{email}/edit', 'EmployeeEmailController@update')->name('update');
        Route::get('{email}/mark-completed', 'EmployeeEmailController@markCompleted')->name('completed');
        Route::get('{email}/mark-completed-notify', 'EmployeeEmailController@completedNotifyOnly')->name('completed-notify');
        Route::get('{email}/mark-rejected', 'EmployeeEmailController@markRejected')->name('rejected');
        Route::get('{email}/delete', 'EmployeeEmailController@delete')->name('delete');

        // Type
        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('', 'EmployeeTypeController@index')->name('list');
            Route::post('create', 'EmployeeTypeController@store')->name('store');
            Route::get('{type}/edit', 'EmployeeTypeController@edit')->name('edit');
            Route::post('{type}/edit', 'EmployeeTypeController@update')->name('update');
            Route::get('{type}/delete', 'EmployeeTypeController@delete')->name('delete');
        });
    });

    // Teacher
    Route::group(['prefix' => 'teacher', 'as' => 'teacher::'], function () {
        Route::get('', 'TeacherController@index')->name('list');
        Route::get('create', 'TeacherController@create')->name('create');
        Route::post('create', 'TeacherController@store')->name('store');
        Route::get('{teacher}/pdf', 'TeacherController@pdf')->name('pdf');
        Route::get('{teacher}/detail', 'TeacherController@detail')->name('detail');
        Route::get('{teacher}/mark-rejected', 'TeacherController@markRejected')->name('mark-rejected');
        Route::get('{teacher}/mark-activate', 'TeacherController@markActivate')->name('mark-activate');
        Route::get('{teacher}/activate-notify', 'TeacherController@activateNotify')->name('activate-notify');
        Route::get('{teacher}/delete', 'TeacherController@delete')->name('delete');
        Route::get('{teacher}/advance', 'TeacherController@advanceOptionList')->name('advance');
        Route::get('{teacher}/edit', 'TeacherController@edit')->name('edit');
        Route::post('{teacher}/edit', 'TeacherController@update')->name('update');
        Route::get('export', 'TeacherController@export')->name('export');

        Route::get('search', 'TeacherController@jsonSearch')->name('search');

        Route::group(['prefix' => 'designation', 'as' => 'designation::'], function () {
            Route::get('', 'TeacherController@designation')->name('list');
            Route::post('create', 'TeacherController@designationStore')->name('create');
            Route::get('{designation}/edit', 'TeacherController@designationEdit')->name('edit');
            Route::post('{designation}/edit', 'TeacherController@designationUpdate')->name('update');
        });

        Route::group(['prefix' => '{teacher}/education', 'as' => 'education::'], function () {
            Route::get('', 'TeacherController@educationList')->name('list');
            Route::get('create', 'TeacherController@educationCreate')->name('create');
            Route::post('create', 'TeacherController@educationStore')->name('store');
            Route::get('{education}/delete', 'TeacherController@educationDelete')->name('delete');
            Route::get('{education}/edit', 'TeacherController@educationEdit')->name('edit');
            Route::post('{education}/edit', 'TeacherController@educationUpdate')->name('update');
        });

        Route::group(['prefix' => '{teacher}/teaching', 'as' => 'teaching::'], function () {
            Route::get('', 'TeacherController@teachingList')->name('list');
            Route::get('create', 'TeacherController@teachingCreate')->name('create');
            Route::post('create', 'TeacherController@teachingStore')->name('store');
            Route::get('{teaching}/delete', 'TeacherController@teachingDelete')->name('delete');
            Route::get('{teaching}/edit', 'TeacherController@teachingEdit')->name('edit');
            Route::post('{teaching}/edit', 'TeacherController@teachingUpdate')->name('update');
        });

        Route::group(['prefix' => '{teacher}/activity', 'as' => 'activity::'], function () {
            Route::get('', 'TeacherController@activityList')->name('list');
            Route::get('create', 'TeacherController@activityCreate')->name('create');
            Route::post('create', 'TeacherController@activityStore')->name('store');
            Route::get('{activity}/delete', 'TeacherController@activityDelete')->name('delete');
            Route::get('{activity}/edit', 'TeacherController@activityEdit')->name('edit');
            Route::post('{activity}/edit', 'TeacherController@activityUpdate')->name('update');
        });

        Route::group(['prefix' => '{teacher}/experience', 'as' => 'experience::'], function () {
            Route::get('', 'TeacherController@experienceList')->name('list');
            Route::get('create', 'TeacherController@experienceCreate')->name('create');
            Route::post('create', 'TeacherController@experienceStore')->name('store');
            Route::get('{experience}/delete', 'TeacherController@experienceDelete')->name('delete');
            Route::get('{experience}/edit', 'TeacherController@experienceEdit')->name('edit');
            Route::post('{experience}/edit', 'TeacherController@experienceUpdate')->name('update');
        });

        Route::group(['prefix' => '{teacher}/publication', 'as' => 'publication::'], function () {
            Route::get('', 'TeacherController@publicationList')->name('list');
            Route::get('create', 'TeacherController@publicationCreate')->name('create');
            Route::post('create', 'TeacherController@publicationStore')->name('store');
            Route::get('{publication}/delete', 'TeacherController@publicationDelete')->name('delete');
            Route::get('{publication}/edit', 'TeacherController@publicationEdit')->name('edit');
            Route::post('{publication}/edit', 'TeacherController@publicationUpdate')->name('update');
        });

        Route::group(['prefix' => 'status', 'as' => 'status::'], function () {
            Route::get('', 'TeacherController@status')->name('list');
            Route::post('create', 'TeacherController@statusStore')->name('create');
            Route::get('{status}/edit', 'TeacherController@statusEdit')->name('edit');
            Route::post('{status}/edit', 'TeacherController@statusUpdate')->name('update');
        });

        Route::group(['prefix' => 'publication/type', 'as' => 'publication::type::'], function () {
            Route::get('', 'TeacherController@typeList')->name('list');
            Route::post('create', 'TeacherController@typeStore')->name('create');
            Route::get('{type}/edit', 'TeacherController@typeEdit')->name('edit');
            Route::post('{type}/edit', 'TeacherController@typeUpdate')->name('update');
        });
    });

    // Officer
    Route::group(['prefix' => 'officer', 'as' => 'officer::'], function () {
        Route::get('', 'OfficerController@index')->name('list');
        Route::get('create', 'OfficerController@create')->name('create');
        Route::post('create', 'OfficerController@store')->name('store');
        Route::get('{officer}/delete', 'OfficerController@delete')->name('delete');
        Route::get('{officer}/edit', 'OfficerController@edit')->name('edit');
        Route::post('{officer}/edit', 'OfficerController@update')->name('update');

        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('', 'OfficerController@typeList')->name('list');
            Route::post('create', 'OfficerController@typeStore')->name('create');
            Route::get('{type}/edit', 'OfficerController@typeEdit')->name('edit');
            Route::post('{type}/edit', 'OfficerController@typeUpdate')->name('update');
        });
    });

    // Program
    Route::group(['prefix' => 'program', 'as' => 'program::'], function () {
        Route::get('', 'ProgramController@index')->name('list');
        Route::get('create', 'ProgramController@create')->name('create');
        Route::post('create', 'ProgramController@store')->name('store');
        Route::get('{program}/delete', 'ProgramController@delete')->name('delete');
        Route::get('{program}/edit', 'ProgramController@edit')->name('edit');
        Route::post('{program}/edit', 'ProgramController@update')->name('update');

        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('', 'ProgramController@typeList')->name('list');
            Route::post('create', 'ProgramController@typeStore')->name('create');
            Route::get('{type}/edit', 'ProgramController@typeEdit')->name('edit');
            Route::post('{type}/edit', 'ProgramController@typeUpdate')->name('update');
        });
    });

    // Link
    Route::group(['prefix' => 'link', 'as' => 'link::'], function () {
        Route::get('', 'LinkController@index')->name('list');
        Route::get('create', 'LinkController@create')->name('create');
        Route::post('create', 'LinkController@store')->name('store');
        Route::get('{link}/delete', 'LinkController@delete')->name('delete');
        Route::get('{link}/edit', 'LinkController@edit')->name('edit');
        Route::post('{link}/edit', 'LinkController@update')->name('update');

        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('', 'LinkController@typeList')->name('list');
            Route::post('create', 'LinkController@typeStore')->name('create');
            Route::get('{type}/edit', 'LinkController@typeEdit')->name('edit');
            Route::post('{type}/edit', 'LinkController@typeUpdate')->name('update');
        });
    });

    // Research
    Route::group(['prefix' => 'research', 'as' => 'research::'], function () {
        Route::get('', 'ResearchController@index')->name('list');
        Route::get('create', 'ResearchController@create')->name('create');
        Route::post('create', 'ResearchController@store')->name('store');
        Route::get('{research}/delete', 'ResearchController@delete')->name('delete');
        Route::get('{research}/edit', 'ResearchController@edit')->name('edit');
        Route::post('{research}/edit', 'ResearchController@update')->name('update');

        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('', 'ResearchController@typeList')->name('list');
            Route::post('create', 'ResearchController@typeStore')->name('create');
            Route::get('{type}/edit', 'ResearchController@typeEdit')->name('edit');
            Route::post('{type}/edit', 'ResearchController@typeUpdate')->name('update');
        });
    });

    // Administration
    Route::group(['prefix' => 'administration', 'as' => 'administration::'], function () {
        Route::get('', 'AdministrationController@index')->name('list');
        Route::get('create', 'AdministrationController@create')->name('create');
        Route::post('create', 'AdministrationController@store')->name('store');
        Route::get('{member}/delete', 'AdministrationController@delete')->name('delete');
        Route::get('{member}/edit', 'AdministrationController@edit')->name('edit');
        Route::post('{member}/edit', 'AdministrationController@update')->name('update');

        Route::group(['prefix' => 'role', 'as' => 'role::'], function () {
            Route::get('', 'AdministrationController@roleList')->name('list');
            Route::get('create', 'AdministrationController@roleCreate')->name('create');
            Route::post('create', 'AdministrationController@roleStore')->name('store');
            Route::get('{role}/edit', 'AdministrationController@roleEdit')->name('edit');
            Route::post('{role}/edit', 'AdministrationController@roleUpdate')->name('update');

            Route::get('{member}/assign', 'AdministrationController@assignRole')->name('assign');
            Route::post('{member}/assign', 'AdministrationController@assignRoleStore')->name('assign::save');

            Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
                Route::get('', 'AdministrationController@roleTypeList')->name('list');
                Route::post('create', 'AdministrationController@roleTypeStore')->name('create');
                Route::get('{type}/edit', 'AdministrationController@roleTypeEdit')->name('edit');
                Route::post('{type}/edit', 'AdministrationController@roleTypeUpdate')->name('update');
            });
        });
    });

    // Legal Certificate
    Route::group(['prefix' => 'certificate', 'as' => 'certificate::'], function () {
        Route::get('', 'LegalCertificateController@index')->name('list');
        Route::get('create', 'LegalCertificateController@create')->name('create');
        Route::post('create', 'LegalCertificateController@store')->name('store');
        Route::get('{certificate}/delete', 'LegalCertificateController@delete')->name('delete');
        Route::get('{certificate}/edit', 'LegalCertificateController@edit')->name('edit');
        Route::post('{certificate}/edit', 'LegalCertificateController@update')->name('update');

        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('', 'LegalCertificateController@typeList')->name('list');
            Route::post('create', 'LegalCertificateController@typeStore')->name('create');
            Route::get('{type}/delete', 'LegalCertificateController@typeDelete')->name('delete');
            Route::get('{type}/edit', 'LegalCertificateController@typeEdit')->name('edit');
            Route::post('{type}/edit', 'LegalCertificateController@typeUpdate')->name('update');
        });
    });

    // Hall
    Route::group(['prefix' => 'hall', 'as' => 'hall::'], function () {
        Route::get('', 'HallController@index')->name('list');
        Route::get('create', 'HallController@create')->name('create');
        Route::post('create', 'HallController@store')->name('store');
        Route::get('{hall}/delete', 'HallController@delete')->name('delete');
        Route::get('{hall}/edit', 'HallController@edit')->name('edit');
        Route::post('{hall}/edit', 'HallController@update')->name('update');

        Route::get('{hall}/assign', 'HallController@assign')->name('assign');
        Route::post('{hall}/assign', 'HallController@assignSave')->name('assign::save');
        Route::get('{hall}/unassign', 'HallController@unassign')->name('unassign');

        Route::group(['prefix' => '{hall}/program', 'as' => 'program::'], function () {
            Route::get('', 'ProgramController@departmentCenterHallList')->name('list');
            Route::get('create', 'ProgramController@createForCenterDepartmentHall')->name('create');
        });
    });

    // Facility
    Route::group(['prefix' => 'facility', 'as' => 'facility::'], function () {
        Route::get('', 'FacilityController@index')->name('list');
        Route::get('create', 'FacilityController@create')->name('create');
        Route::post('create', 'FacilityController@store')->name('store');
        Route::get('{facility}/delete', 'FacilityController@delete')->name('delete');
        Route::get('{facility}/edit', 'FacilityController@edit')->name('edit');
        Route::post('{facility}/edit', 'FacilityController@update')->name('update');
    });

    // Custom Page
    Route::group(['prefix' => 'custom-page', 'as' => 'custom::page::'], function () {
        Route::get('', 'CustomPageController@index')->name('list');
        Route::get('create', 'CustomPageController@create')->name('create');
        Route::post('create', 'CustomPageController@store')->name('store');
        Route::get('{page}/delete', 'CustomPageController@delete')->name('delete');
        Route::get('{page}/edit', 'CustomPageController@edit')->name('edit');
        Route::post('{page}/edit', 'CustomPageController@update')->name('update');
    });

    // Menu
    Route::group(['prefix' => 'menu', 'as' => 'menu::'], function () {
        Route::get('', 'MenuController@index')->name('list');
        Route::get('create', 'MenuController@create')->name('create');
        Route::post('create', 'MenuController@store')->name('store');
        Route::get('{menu}/delete', 'MenuController@delete')->name('delete');
        Route::get('{menu}/edit', 'MenuController@edit')->name('edit');
        Route::post('{menu}/edit', 'MenuController@update')->name('update');

        Route::group(['prefix' => '{menu}/submenu', 'as' => 'submenu::'], function () {
            Route::get('', 'MenuController@subMenuList')->name('list');
            Route::get('create', 'MenuController@subMenuCreate')->name('create');
            Route::post('create', 'MenuController@subMenuStore')->name('store');
            Route::get('{submenu}/delete', 'MenuController@subMenuDelete')->name('delete');
            Route::get('{submenu}/edit', 'MenuController@subMenuEdit')->name('edit');
            Route::post('{submenu}/edit', 'MenuController@subMenuUpdate')->name('update');
        });
    });

    // Uploaded File
    Route::group(['prefix' => 'file', 'as' => 'file::'], function () {
        Route::get('', 'UploadedFileController@index')->name('list');
        Route::get('create', 'UploadedFileController@create')->name('create');
        Route::post('create', 'UploadedFileController@store')->name('store');
        Route::get('{file}/delete', 'UploadedFileController@delete')->name('delete');
        Route::get('{file}/edit', 'UploadedFileController@edit')->name('edit');
        Route::post('{file}/edit', 'UploadedFileController@update')->name('update');
    });

    // Settings
    Route::group(['prefix' => 'setting', 'as' => 'setting::'], function () {
        Route::get('', 'SettingsController@edit')->name('edit');
        Route::post('', 'SettingsController@update')->name('update');
        Route::get('reorder', 'SettingsController@reorder')->name('reorder');
    });

    // E-mail Broadcast
    Route::group(['prefix' => 'email', 'as' => 'email::'], function () {
        Route::get('broadcast', 'SettingsController@emailBroadcast')->name('broadcast');
        Route::post('broadcast', 'SettingsController@emailBroadcastSend')->name('broadcast::send');
    });

    // Journal
    Route::group(['prefix' => 'journal', 'as' => 'journal::'], function () {
        Route::get('', 'JournalController@index')->name('list');
        Route::get('create', 'JournalController@create')->name('create');
        Route::post('create', 'JournalController@store')->name('store');
        Route::get('{journal}/delete', 'JournalController@delete')->name('delete');
        Route::get('{journal}/edit', 'JournalController@edit')->name('edit');
        Route::post('{journal}/edit', 'JournalController@update')->name('update');

        // Content
        Route::group(['prefix' => '{journal}/content', 'as' => 'content::'], function () {
            Route::get('', 'JournalController@contentList')->name('list');
            Route::get('create', 'JournalController@contentCreate')->name('create');
            Route::post('create', 'JournalController@contentStore')->name('store');
            Route::get('{content}/delete', 'JournalController@contentDelete')->name('delete');
            Route::get('{content}/edit', 'JournalController@contentEdit')->name('edit');
            Route::post('{content}/edit', 'JournalController@contentUpdate')->name('update');
        });
    });

    // Home Controller
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/bulk-password-assign', 'DashboardController@bulkPasswordAssign')->name('bulk-password-assign');

    // Department Routes

    Route::group(['prefix' => 'department', 'as' => 'department::'], function () {
        Route::get('', 'DepartmentController@index')->name('list');
        Route::get('create', 'DepartmentController@create')->name('create');
        Route::post('create', 'DepartmentController@store')->name('store');
        Route::get('{department}/edit', 'DepartmentController@edit')->name('edit');
        Route::post('{department}/edit', 'DepartmentController@update')->name('update');

        Route::group(['prefix' => '{department}'], function () {
            Route::get('assign', 'DepartmentController@assignView')->name('assign');
            Route::post('assign', 'DepartmentController@assign')->name('assign::save');
            Route::get('unassign', 'DepartmentController@unassign')->name('unassign');
            Route::get('advance', 'DepartmentController@advanceOptionList')->name('advance');
            Route::get('upload', 'DepartmentController@upload')->name('upload');
            Route::get('upload/create', 'DepartmentController@uploadCreate')->name('upload::create');
            Route::post('upload', 'DepartmentController@uploadSave')->name('upload::save');
            Route::get('upload/{file}/delete', 'DepartmentController@uploadDelete')->name('upload::delete');
            Route::get('upload/{file}/edit', 'DepartmentController@uploadEdit')->name('upload::edit');
            Route::post('upload/{file}/edit', 'DepartmentController@uploadUpdate')->name('upload::update');

            Route::group(['prefix' => 'officer', 'as' => 'officer::'], function () {
                Route::get('', 'OfficerController@departmentCenterOfficerList')->name('list');
                Route::get('create', 'OfficerController@create')->name('create');
            });

            Route::group(['prefix' => 'program', 'as' => 'program::'], function () {
                Route::get('', 'ProgramController@departmentCenterHallList')->name('list');
                Route::get('create', 'ProgramController@createForCenterDepartmentHall')->name('create');
            });

            Route::group(['prefix' => '/facility', 'as' => 'facility::'], function () {
                Route::get('', 'FacilityController@departmentCenterList')->name('list');
                Route::get('create', 'FacilityController@createForCenterDepartment')->name('create');
            });

            Route::group(['prefix' => '/teacher', 'as' => 'teacher::'], function () {
                Route::get('', 'TeacherController@departmentTeacher')->name('list');
            });

            Route::group(['prefix' => '/event', 'as' => 'event::'], function () {
                Route::get('', 'SettingsController@departmentEvent')->name('list');
                Route::get('create', 'SettingsController@departmentEventCreate')->name('create');
            });

            Route::group(['prefix' => '/research', 'as' => 'research::'], function () {
                Route::get('', 'ResearchController@departmentResearch')->name('list');
                Route::get('create', 'ResearchController@departmentResearchCreate')->name('create');
            });

            Route::group(['prefix' => '/link', 'as' => 'link::'], function () {
                Route::get('', 'LinkController@departmentLink')->name('list');
                Route::get('create', 'LinkController@departmentLinkCreate')->name('create');
            });

            Route::group(['prefix' => '/gallery', 'as' => 'gallery::image::'], function () {
                Route::get('', 'SettingsController@departmentImageList')->name('list');
                Route::get('create', 'SettingsController@departmentImageCreate')->name('create');
            });
        });

        Route::get('search', 'DepartmentController@jsonSearch')->name('search');
    });

    // Event

    Route::group(['prefix' => 'event', 'as' => 'event::'], function () {
        Route::get('', 'SettingsController@eventList')->name('list');
        Route::get('{discussion}/edit', 'SettingsController@eventEdit')->name('edit');
        Route::post('{discussion}/edit', 'SettingsController@eventUpdate')->name('update');
        Route::get('create', 'SettingsController@eventCreate')->name('create');
        Route::post('create', 'SettingsController@eventStore')->name('store');
        Route::get('{discussion}/upload', 'SettingsController@upload')->name('upload');
        Route::get('{discussion}/delete', 'SettingsController@eventDelete')->name('delete');
        Route::post('{discussion}/upload', 'SettingsController@uploadSave')->name('upload::save');
        Route::get('{discussion}/file/{file}/delete', 'SettingsController@uploadFileDelete')->name('upload::delete');
        Route::get('{discussion}/file/{file}/edit', 'SettingsController@uploadFileEdit')->name('upload::edit');
        Route::post('{discussion}/file/{file}/edit', 'SettingsController@uploadFileUpdate')->name('upload::update');

        Route::group(['prefix' => 'type', 'as' => 'type::'], function () {
            Route::get('create', 'SettingsController@eventTypeCreate')->name('create');
            Route::post('create', 'SettingsController@eventTypeStore')->name('store');
            Route::get('', 'SettingsController@eventTypeList')->name('list');
            Route::get('{event}/edit', 'SettingsController@eventTypeEdit')->name('edit');
            Route::post('{event}/edit', 'SettingsController@eventTypeUpdate')->name('update');
        });
    });

    // Users Routes

    Route::group(['prefix' => 'users/type', 'as' => 'users::type::'], function () {
        Route::get('', 'UsersController@typeList')->name('list');
        Route::post('create', 'UsersController@typeStore')->name('create');
        Route::get('{type}/edit', 'UsersController@typeEdit')->name('edit');
        Route::post('{type}/edit', 'UsersController@typeUpdate')->name('update');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('', 'UsersController@index')->name('users');
        Route::get('change-password', 'UsersController@changePassword')->name('change-password');
        Route::post('change-password', 'UsersController@changePasswordUpdate')->name('update-password');
        Route::get('create', 'UsersController@create')->name('users_create');
        Route::post('create', 'UsersController@store')->name('users_store');
        Route::get('reorder', 'UsersController@reOrder')->name('user_reorder');
        Route::get('{id}', 'UsersController@show')->name('users_profile');
        Route::get('{id}/force-login', 'UsersController@forceLogin')->name('users_force_login');
        Route::get('{user}/edit', 'UsersController@edit')->name('users_edit');
        Route::post('{user}/edit', 'UsersController@update')->name('users_update');
        Route::get('{id}/roles', 'UsersController@editRoles')->name('users_roles');
        Route::post('{id}/roles', 'UsersController@setRoles');
        Route::get('{user}/delete', 'UsersController@destroy')->name('users_delete');

        Route::group(['prefix' => '{user}/roles/assign'], function () {
            Route::get('department', 'UsersController@roleDepartmentAssign')->name('users_role_department_assign');
            Route::post('department', 'UsersController@roleDepartmentAssignStore')->name('users_role_department_assign_save');
            Route::get('faculty', 'UsersController@roleFacultyAssign')->name('users_role_faculty_assign');
            Route::post('faculty', 'UsersController@roleFacultyAssignStore')->name('users_role_faculty_assign_save');
            Route::get('hall', 'UsersController@roleHallAssign')->name('users_role_hall_assign');
            Route::post('hall', 'UsersController@roleHallAssignStore')->name('users_role_hall_assign_save');
            Route::get('center', 'UsersController@roleCenterAssign')->name('users_role_center_assign');
            Route::post('center', 'UsersController@roleCenterAssignStore')->name('users_role_center_assign_save');
            Route::get('event', 'UsersController@roleEventAssign')->name('users_role_event_assign');
            Route::post('event', 'UsersController@roleEventAssignStore')->name('users_role_event_assign_save');

            Route::get('session', 'UsersController@roleAdmissionSessionAssign')->name('users_role_admission_session_assign');
            Route::post('session', 'UsersController@roleAdmissionSessionAssignStore')->name('users_role_admission_session_assign_save');
        });

        Route::group(['prefix' => '{user}/roles/unassign/'], function () {
            Route::get('{teacher}/teacher', 'UsersController@roleTeacherUnassign')->name('users_role_teacher_unassign');
            Route::get('{department}/department', 'UsersController@roleDepartmentUnassign')->name('users_role_department_unassign');
            Route::get('{faculty}/faculty', 'UsersController@roleFacultyUnassign')->name('users_role_faculty_unassign');
            Route::get('{hall}/hall', 'UsersController@roleHallUnassign')->name('users_role_hall_unassign');
            Route::get('{center}/center', 'UsersController@roleCenterUnassign')->name('users_role_center_unassign');
            Route::get('{event}/event', 'UsersController@roleEventUnassign')->name('users_role_event_unassign');
            Route::get('{session}/session', 'UsersController@roleAdmissionSessionUnassign')->name('users_role_admission_session_unassign');
        });
    });

    Route::get('/users/settings', 'UsersController@editSettings')->name('users_settings');
    Route::post('/users/settings', 'UsersController@updateSettings');

    // Roles Routes
    Route::get('/roles', 'RolesController@index')->name('roles');

    Route::get('/roles/create', 'RolesController@create')->name('roles_create');
    Route::post('/roles/create', 'RolesController@store');

    Route::get('/roles/{id}', 'RolesController@show')->name('roles_show');

    Route::get('/roles/{id}/edit', 'RolesController@edit')->name('roles_edit');
    Route::post('/roles/{id}/edit', 'RolesController@update');

    Route::get('/roles/{id}/permissions', 'RolesController@editPermissions')->name('roles_permissions');
    Route::post('/roles/{id}/permissions', 'RolesController@setPermissions');

    Route::get('/roles/{id}/delete', 'SecurityController@confirm')->name('roles_delete');
    Route::post('/roles/{id}/delete', 'RolesController@destroy');

    // Permissions Routes
    Route::get('/permissions', 'PermissionsController@index')->name('permissions');

    Route::get('/permissions/create', 'PermissionsController@create')->name('permissions_create');
    Route::post('/permissions/create', 'PermissionsController@store');

    Route::get('/permissions/{id}/edit', 'PermissionsController@edit')->name('permissions_edit');
    Route::post('/permissions/{id}/edit', 'PermissionsController@update');

    Route::get('/permissions/{id}/delete', 'SecurityController@confirm')->name('permissions_delete');
    Route::post('/permissions/{id}/delete', 'PermissionsController@destroy');

    // File Manager
    Route::get('/files', 'FilesController@files')->name('files');

    Route::get('/files/upload', 'FilesController@showUpload')->name('files_upload');
    Route::post('/files/upload', 'FilesController@upload');

    Route::get('/documents/{file}/create', 'DocumentsController@showCreate')->name('documents_create');
    Route::post('/documents/{file}/create', 'DocumentsController@createDocument');

    Route::get('/documents/{slug}/edit', 'DocumentsController@edit')->name('documents_edit');
    Route::post('/documents/{slug}/edit', 'DocumentsController@update');

    Route::get('/documents/{slug}/delete', 'SecurityController@confirm')->name('documents_delete');
    Route::post('/documents/{slug}/delete', 'DocumentsController@delete');

    Route::get('/files/{file}/delete', 'SecurityController@confirm')->name('files_delete');
    Route::post('/files/{file}/delete', 'FilesController@delete');

    Route::get('/files/{file}/download', 'FilesController@fileDownload')->name('files_download');

    //image
//    Route::get('/image/banner', 'SettingsController@getAllBannerImage')->name('image::banner');

    Route::get('/faculty', 'FacultyController@index')->name('faculty::list');
    Route::get('/faculty/create', 'FacultyController@create')->name('faculty::create');
    Route::post('/faculty/create', 'FacultyController@store')->name('faculty::store');
    Route::get('/faculty/{faculty}/edit', 'FacultyController@edit')->name('faculty::edit');
    Route::post('/faculty/{faculty}/edit', 'FacultyController@update')->name('faculty::update');
    Route::get('/faculty/{faculty}/assign', 'FacultyController@assignView')->name('faculty::assign');
    Route::post('/faculty/{faculty}/assign', 'FacultyController@assign')->name('faculty::assign::save');
    Route::get('/faculty/{faculty}/unassign', 'FacultyController@unassign')->name('faculty::unassign');

    Route::get('/gallery/image', 'SettingsController@allImageList')->name('gallery::image::list');
    Route::get('/gallery/image/json-image', 'SettingsController@jsonImageList')->name('gallery::image::json');
    Route::get('/gallery/image/create', 'SettingsController@createImage')->name('gallery::image::create');
    Route::post('/gallery/image/create', 'SettingsController@storeImage')->name('gallery::image::store');

    Route::get('/gallery/{categories}/image/{image}/edit', 'SettingsController@imageDelete')->name('gallery::image::delete');
    Route::get('/gallery/image/{image}/edit', 'SettingsController@imageEdit')->name('gallery::image::edit');
    Route::post('/gallery/image/{image}/update', 'SettingsController@imageUpdate')->name('gallery::image::update');

    Route::get('/gallery/category', 'SettingsController@galleryCategoryList')->name('gallery::category::list');
    Route::get('/gallery/category/create', 'SettingsController@galleryCategoryCreate')->name('gallery::category::create');
    Route::post('/gallery/category/create', 'SettingsController@galleryCategoryStore')->name('gallery::category::store');
    Route::get('/gallery/category/{id}/edit', 'SettingsController@galleryCategoryEdit')->name('gallery::category::edit');
    Route::post('/gallery/category/{id}/edit', 'SettingsController@galleryCategoryUpdate')->name('gallery::category::update');

    Route::post('/image', 'SettingsController@imageSave')->name('image::banner');

    // Profile
    Route::get('/profile', 'ProfileController@edit')->name('profile');
    Route::post('/profile', 'ProfileController@update');

    // About
    Route::get('/about', 'AboutController@index')->name('about');
});


Route::get('{slugOne}/{slugTwo}', function ($slugOne, $slugTwo) {
    $slug = $slugOne. '/'. $slugTwo;
    $customPage = App\CustomPage::getBySlug($slug);
    //dd($customPage);

    if(!$customPage){
        return redirect('not-found');
    }

    $controller = new App\Http\Controllers\Laralum\CustomPageController();

    return $controller->view($slug);
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
