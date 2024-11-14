-- ensure gallery_image_categories table all column have default value for height & width
ALTER TABLE `gallery_image_categories` ADD `width` INT NOT NULL DEFAULT '500' AFTER `max_image_size_in_kb`, ADD `height` INT NOT NULL DEFAULT '500' AFTER `width`;

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `events` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'News', '2020-03-08 10:02:38', '2020-03-08 10:02:38'),
(2, 'Events', '2020-03-08 10:02:50', '2020-03-08 10:02:50'),
(3, 'Tenders', '2020-03-08 10:03:03', '2020-03-08 10:03:03'),
(4, 'Notices', '2020-03-08 10:03:13', '2020-03-08 10:03:13'),
(5, 'Job Circulars', '2020-03-08 10:03:33', '2020-03-08 10:03:33'),
(6, 'Press Releases', '2020-03-08 10:03:44', '2020-03-08 10:03:44'),
(7, 'Result', '2020-03-08 10:03:53', '2020-03-08 10:03:53');

ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


CREATE TABLE `teacher_designations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `teacher_designations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Professor', '2020-03-14 09:55:21', '2020-03-14 10:23:39'),
(2, 'Associate Professor', '2020-03-14 09:55:56', '2020-03-14 09:55:56'),
(3, 'Assistant Professor', '2020-03-14 09:56:13', '2020-03-14 09:56:13'),
(4, 'Lecturer', '2020-03-14 09:56:22', '2020-03-14 09:56:22');

ALTER TABLE `teacher_designations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teacher_designations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


CREATE TABLE `teacher_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `teacher_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Active', '2020-03-14 21:18:59', '2020-03-14 21:32:37'),
(2, 'Leave', '2020-03-14 21:19:09', '2020-03-14 21:19:09'),
(3, 'RETIRED', '2020-03-14 21:19:16', '2020-03-14 21:19:16'),
(4, 'LPR', '2020-03-14 21:19:23', '2020-03-14 21:19:23');


ALTER TABLE `teacher_statuses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teacher_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `discussion_topics` CHANGE `publish_date` `publish_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `discussion_topics` ADD `event_id` INT NULL DEFAULT NULL AFTER `details`;

UPDATE `discussion_topics` SET `event_id`=1 WHERE `type`='NEWS'

UPDATE `discussion_topics` SET `event_id`=2 WHERE `type`='EVENT'

UPDATE `discussion_topics` SET `event_id`=3 WHERE `type`='TENDER'

UPDATE `discussion_topics` SET `event_id`=4 WHERE `type`='NOTICE'

UPDATE `discussion_topics` SET `event_id`=5 WHERE `type`='JOB_CIRCULAR'

UPDATE `discussion_topics` SET `event_id`=6 WHERE `type`='PRESS_RELEASE'

UPDATE `discussion_topics` SET `event_id`=7 WHERE `type`='RESULT'


ALTER TABLE `events` ADD `max_size` INT NOT NULL DEFAULT '500' AFTER `name`, ADD `width` INT NOT NULL DEFAULT '500' AFTER `max_size`, ADD `height` INT NOT NULL DEFAULT '500' AFTER `width`;

ALTER TABLE `discussion_topics` ADD `highlight` INT NOT NULL DEFAULT '0' AFTER `featured_news`;


CREATE TABLE `center_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `max_size` int(11) NOT NULL DEFAULT '500',
  `width` int(11) NOT NULL DEFAULT '300',
  `height` int(11) NOT NULL DEFAULT '300',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `center_types` (`id`, `name`, `max_size`, `width`, `height`, `created_at`, `updated_at`) VALUES
(1, 'CENTER', 500, 300, 300, '2020-03-27 07:38:30', '2020-03-29 03:08:33'),
(2, 'OFFICE', 500, 300, 300, '2020-03-27 07:39:10', '2020-03-27 07:44:09');


ALTER TABLE `center_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `center_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `centers` ADD `type_id` INT NULL DEFAULT NULL AFTER `type`;

UPDATE `centers` SET `type_id`=1 WHERE `type`='CENTER'

UPDATE `centers` SET `type_id`=2 WHERE `type`='OFFICE'

CREATE TABLE `officer_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `max_size` int(11) NOT NULL DEFAULT '500',
  `width` int(11) NOT NULL DEFAULT '300',
  `height` int(11) NOT NULL DEFAULT '300',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `officer_types` (`id`, `name`, `max_size`, `width`, `height`, `created_at`, `updated_at`) VALUES
(1, 'OFFICER', 500, 300, 300, '2020-03-29 03:06:52', '2020-03-29 03:09:16'),
(2, 'STAFF', 500, 300, 300, '2020-03-29 03:07:02', '2020-03-29 03:07:02');

ALTER TABLE `officer_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `officer_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `officers` ADD `type_id` INT NULL DEFAULT NULL AFTER `type`;

UPDATE `officers` SET `type_id`=1 WHERE `type`='OFFICER'

UPDATE `officers` SET `type_id`=2 WHERE `type`='STAFF'

CREATE TABLE `program_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `program_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'PROGRAM', '2020-03-30 03:08:10', '2020-03-30 03:08:10'),
(2, 'CALENDAR', '2020-03-30 03:08:18', '2020-03-30 03:08:32');

ALTER TABLE `program_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `program_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `department_programs` ADD `type_id` INT NULL DEFAULT NULL AFTER `type`;

UPDATE `department_programs` SET `type_id`=1 WHERE `type`='PROGRAM'

UPDATE `department_programs` SET `type_id`=2 WHERE `type`='CALENDAR'

CREATE TABLE `research_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `research_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'JOURNAL', '2020-03-31 05:13:43', '2020-03-31 05:15:54'),
(2, 'CONFERENCE', '2020-03-31 05:13:56', '2020-03-31 05:13:56'),
(3, 'WORKSHOP', '2020-03-31 05:14:04', '2020-03-31 05:14:04');

ALTER TABLE `research_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `research_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `researches` ADD `type_id` INT NULL DEFAULT NULL AFTER `type`;

UPDATE `researches` SET `type_id`=1 WHERE `type`='JOURNAL'

UPDATE `researches` SET `type_id`=2 WHERE `type`='CONFERENCE'

UPDATE `researches` SET `type_id`=3 WHERE `type`='WORKSHOP'

CREATE TABLE `link_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `link_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ACADEMIC', '2020-03-31 10:13:09', '2020-03-31 10:15:52'),
(2, 'ADMISSION', '2020-03-31 10:13:18', '2020-03-31 10:13:18'),
(3, 'DOWNLOAD_FORM', '2020-03-31 10:13:31', '2020-03-31 10:13:31'),
(4, 'OTHER', '2020-03-31 10:13:43', '2020-03-31 10:13:43'),
(5, 'USEFUL_LINK', '2020-03-31 10:14:06', '2020-03-31 10:14:06'),
(6, 'MISC', '2020-03-31 10:14:19', '2020-03-31 10:14:19');

ALTER TABLE `link_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `link_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `links` ADD `type_id` INT NULL DEFAULT NULL AFTER `type`;

UPDATE `links` SET `type_id`=1 WHERE `type`='ACADEMIC'

UPDATE `links` SET `type_id`=2 WHERE `type`='ADMISSION'

UPDATE `links` SET `type_id`=3 WHERE `type`='DOWNLOAD_FORM'

UPDATE `links` SET `type_id`=4 WHERE `type`='OTHER'

UPDATE `links` SET `type_id`=5 WHERE `type`='USEFUL_LINK'

UPDATE `links` SET `type_id`=6 WHERE `type`='MISC'

CREATE TABLE `legal_certificate_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `legal_certificate_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'NOC', '2020-04-02 04:06:47', '2020-04-02 04:10:33'),
(2, 'GO', '2020-04-02 04:10:18', '2020-04-02 04:10:18');


ALTER TABLE `legal_certificate_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `legal_certificate_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `legal_certificates` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `legal_certificates` ADD `type_id` INT NULL DEFAULT NULL AFTER `type`;

UPDATE `legal_certificates` SET `type_id`=1 WHERE `type`='NOC'

UPDATE `legal_certificates` SET `type_id`=2 WHERE `type`='GO'

CREATE TABLE `administrative_role_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `administrative_role_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'CHANCELLOR', '2020-04-03 00:09:11', '2020-04-03 00:13:19'),
(2, 'VICE_CHANCELLOR', '2020-04-03 00:09:19', '2020-04-03 00:09:19'),
(3, 'PRO_VICE_CHANCELLOR', '2020-04-03 00:09:25', '2020-04-03 00:09:25'),
(4, 'TREASURER', '2020-04-03 00:09:54', '2020-04-03 00:09:54'),
(5, 'SENATE', '2020-04-03 00:10:00', '2020-04-03 00:10:00'),
(6, 'SYNDICATE', '2020-04-03 00:10:30', '2020-04-03 00:10:30'),
(7, 'FINANCE_COMMITTEE', '2020-04-03 00:10:51', '2020-04-03 00:10:51'),
(8, 'ACADEMIC_COUNCIL', '2020-04-03 00:11:05', '2020-04-03 00:11:05'),
(9, 'REGISTER', '2020-04-03 00:11:12', '2020-04-03 00:11:12'),
(10, 'OTHER', '2020-04-03 00:11:18', '2020-04-03 00:11:18');

ALTER TABLE `administrative_role_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `administrative_role_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `administrative_roles` ADD `type_id` INT NULL DEFAULT NULL AFTER `type`;

UPDATE `administrative_roles` SET `type_id`=1 WHERE `type`='CHANCELLOR'

UPDATE `administrative_roles` SET `type_id`=2 WHERE `type`='VICE_CHANCELLOR'

UPDATE `administrative_roles` SET `type_id`=3 WHERE `type`='PRO_VICE_CHANCELLOR'

UPDATE `administrative_roles` SET `type_id`=4 WHERE `type`='TREASURER'

UPDATE `administrative_roles` SET `type_id`=5 WHERE `type`='SENATE'

UPDATE `administrative_roles` SET `type_id`=6 WHERE `type`='SYNDICATE'

UPDATE `administrative_roles` SET `type_id`=7 WHERE `type`='FINANCE_COMMITTEE'

UPDATE `administrative_roles` SET `type_id`=8 WHERE `type`='ACADEMIC_COUNCIL'

UPDATE `administrative_roles` SET `type_id`=9 WHERE `type`='REGISTER'

UPDATE `administrative_roles` SET `type_id`=10 WHERE `type`='OTHER'

CREATE TABLE `user_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ACTIVE', '2020-04-04 21:34:02', '2020-04-04 21:36:07'),
(2, 'INACTIVE', '2020-04-04 21:34:11', '2020-04-04 21:34:11'),
(3, 'DELETED', '2020-04-04 21:34:18', '2020-04-04 21:34:18'),
(4, 'LOCKED', '2020-04-04 21:34:24', '2020-04-04 21:34:24');

ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `users` ADD `state_id` INT NULL DEFAULT NULL AFTER `state`;

UPDATE `users` SET `state_id`=1 WHERE `state`='ACTIVE'

UPDATE `users` SET `state_id`=2 WHERE `state`='INACTIVE'

UPDATE `users` SET `state_id`=3 WHERE `state`='DELETED'

UPDATE `users` SET `state_id`=4 WHERE `state`='LOCKED'

CREATE TABLE `teacher_publication_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `teacher_publication_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'JOURNAL_PAPER', '2020-04-06 03:41:05', '2020-04-06 03:43:46'),
(2, 'CONFERENCE_PAPER', '2020-04-06 03:41:16', '2020-04-06 03:41:16'),
(3, 'SEMINAR', '2020-04-06 03:41:22', '2020-04-06 03:41:22'),
(4, 'PATENT', '2020-04-06 03:41:32', '2020-04-06 03:41:32'),
(5, 'BOOK', '2020-04-06 03:41:37', '2020-04-06 03:41:37'),
(6, 'WORKSHOP', '2020-04-06 03:41:48', '2020-04-06 03:41:48'),
(7, 'OTHER', '2020-04-06 03:41:56', '2020-04-06 03:41:56');

ALTER TABLE `teacher_publication_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teacher_publication_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `teacher_publications` ADD `teacher_publication_type_id` INT NULL DEFAULT NULL AFTER `teacher_publication_type`;

UPDATE `teacher_publications` SET `teacher_publication_type_id`=1 WHERE `teacher_publication_type`='JOURNAL_PAPER'

UPDATE `teacher_publications` SET `teacher_publication_type_id`=2 WHERE `teacher_publication_type`='CONFERENCE_PAPER'

UPDATE `teacher_publications` SET `teacher_publication_type_id`=3 WHERE `teacher_publication_type`='SEMINAR'

UPDATE `teacher_publications` SET `teacher_publication_type_id`=4 WHERE `teacher_publication_type`='PATENT'

UPDATE `teacher_publications` SET `teacher_publication_type_id`=5 WHERE `teacher_publication_type`='BOOK'

UPDATE `teacher_publications` SET `teacher_publication_type_id`=6 WHERE `teacher_publication_type`='WORKSHOP'

UPDATE `teacher_publications` SET `teacher_publication_type_id`=7 WHERE `teacher_publication_type`='OTHER'

CREATE TABLE `user_events` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user_events`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users` ADD `remember_token` VARCHAR(100) NULL DEFAULT NULL AFTER `password`;

ALTER TABLE `settings` ADD `custom_css` LONGTEXT NULL DEFAULT NULL AFTER `welcome_message`, ADD `custom_js` LONGTEXT NULL DEFAULT NULL AFTER `custom_css`;

ALTER TABLE `settings` ADD `home_first_section_event` VARCHAR(64) NULL DEFAULT NULL AFTER `custom_js`, ADD `home_second_section_event` VARCHAR(64) NULL DEFAULT NULL AFTER `home_first_section_event`, ADD `home_third_section_event` VARCHAR(64) NULL DEFAULT NULL AFTER `home_second_section_event`, ADD `department_event` VARCHAR(64) NULL DEFAULT NULL AFTER `home_third_section_event`;

ALTER TABLE `settings` ADD `default_password_new_user` VARCHAR(64) NULL DEFAULT NULL AFTER `department_event`;

ALTER TABLE `settings` ADD `frontend_pagination_number` INT(4) NOT NULL DEFAULT '20' AFTER `default_password_new_user`, ADD `backend_pagination_number` INT(4) NOT NULL DEFAULT '30' AFTER `frontend_pagination_number`;

ALTER TABLE `administrative_members` ADD `sorting_order` INT(3) NOT NULL DEFAULT '99' AFTER `biography`;

ALTER TABLE `teacher_publications` CHANGE `sorting_order` `sorting_order` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `teacher_activities` CHANGE `sorting_order` `sorting_order` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `teacher_educations` CHANGE `sorting_order` `sorting_order` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `teacher_experiences` CHANGE `sorting_order` `sorting_order` INT(11) NOT NULL DEFAULT '0';

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

ALTER TABLE `discussion_topics` ADD `spotlight` TINYINT NOT NULL DEFAULT '0' AFTER `highlight`;

ALTER TABLE `settings` ADD `spotlight_number` INT(2) NOT NULL DEFAULT '5' AFTER `backend_pagination_number`;

ALTER TABLE `teacher_publications` ADD `author_name` VARCHAR(255) NULL DEFAULT NULL AFTER `name`, ADD `publication_year` VARCHAR(255) NULL DEFAULT NULL AFTER `author_name`, ADD `journal_name` VARCHAR(255) NULL DEFAULT NULL AFTER `publication_year`, ADD `volume` VARCHAR(255) NULL DEFAULT NULL AFTER `journal_name`, ADD `issue` VARCHAR(255) NULL DEFAULT NULL AFTER `volume`, ADD `page` VARCHAR(255) NULL DEFAULT NULL AFTER `issue`;

CREATE TABLE `teacher_teachings` (
  `id` bigint(20) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` bigint(20) NOT NULL DEFAULT '-1',
  `updated_by` bigint(20) NOT NULL DEFAULT '-1',
  `teacher_id` bigint(20) NOT NULL,
  `institute` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(4) NOT NULL DEFAULT '0',
  `sorting_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `teacher_teachings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teacher_teachings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `teacher_teachings` CHANGE `institute` `course_code` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE `teacher_teachings` CHANGE `period` `course_title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `teacher_publications` ADD `conference_location` VARCHAR(255) NULL DEFAULT NULL AFTER `journal_name`;

ALTER TABLE `teacher_publications` ADD `doi` VARCHAR(255) NULL DEFAULT NULL AFTER `url`;

ALTER TABLE `teacher_publications` CHANGE `doi` `url2` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `teacher_teachings` ADD `semester` VARCHAR(255) NULL DEFAULT NULL AFTER `course_title`;

ALTER TABLE `teacher_publication_types` ADD `sorting_order` INT(3) NOT NULL DEFAULT '0' AFTER `name`;

ALTER TABLE `users` ADD `last_login_at` DATETIME NULL DEFAULT NULL AFTER `remember_token`, ADD `last_login_ip` VARCHAR(255) NULL DEFAULT NULL AFTER `last_login_at`, ADD `last_login_device` VARCHAR(255) NULL DEFAULT NULL AFTER `last_login_ip`, ADD `last_login_operating` VARCHAR(255) NULL DEFAULT NULL AFTER `last_login_device`;

ALTER TABLE `center_files` ADD `external_link` VARCHAR(255) NULL DEFAULT NULL AFTER `path`;

ALTER TABLE `centers` ADD `config` VARCHAR(255) NULL DEFAULT '{\"service\":\"Services \\/ Facilities\",\"download\":\"Download\",\"employee\":\"Employee\"}' AFTER `description`;

ALTER TABLE `departments` ADD `config` VARCHAR(255) NULL DEFAULT '{\"program\":\"Academic Programs\",\"research\":\"Research\",\"facility\":\"Facility\",\"link\":\"Important Links\",\"file\":\"Download Files\"}' AFTER `name`;

ALTER TABLE `teachers` ADD `google_scholar` VARCHAR(255) NULL DEFAULT NULL AFTER `designation_text`, ADD `research_gate` VARCHAR(255) NULL DEFAULT NULL AFTER `google_scholar`, ADD `orcid` VARCHAR(255) NULL DEFAULT NULL AFTER `research_gate`, ADD `facebook` VARCHAR(255) NULL DEFAULT NULL AFTER `orcid`, ADD `twitter` VARCHAR(255) NULL DEFAULT NULL AFTER `facebook`, ADD `linkedin` VARCHAR(255) NULL DEFAULT NULL AFTER `twitter`;

CREATE TABLE `student_email_applies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admission_session_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `hall_id` bigint(20) UNSIGNED DEFAULT NULL,
  `global_status_id` bigint(20) UNSIGNED NOT NULL,
  `updated_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `existing_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `student_email_applies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_email_applies_username_unique` (`username`),
  ADD KEY `student_email_applies_admission_session_id_foreign` (`admission_session_id`),
  ADD KEY `student_email_applies_program_id_foreign` (`program_id`),
  ADD KEY `student_email_applies_global_status_id_foreign` (`global_status_id`);

ALTER TABLE `student_email_applies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `student_email_applies`
  ADD CONSTRAINT `student_email_applies_admission_session_id_foreign` FOREIGN KEY (`admission_session_id`) REFERENCES `admission_sessions` (`id`),
  ADD CONSTRAINT `student_email_applies_global_status_id_foreign` FOREIGN KEY (`global_status_id`) REFERENCES `global_statuses` (`id`),
  ADD CONSTRAINT `student_email_applies_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`);


CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sorting_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `programs` (`id`, `name`, `sorting_order`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor', 1, '2020-07-22 17:39:35', '2020-07-22 17:39:35'),
(3, 'Master', 2, '2020-07-22 17:41:30', '2020-07-22 17:41:30'),
(4, 'MPhil', 3, '2020-07-22 17:41:38', '2020-07-22 17:41:38'),
(5, 'Ph.D', 4, '2020-07-22 17:41:42', '2020-07-23 14:35:16'),
(7, 'Weekend', 5, '2020-07-26 08:40:15', '2020-07-26 08:40:15');


ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `programs_name_unique` (`name`);


ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

CREATE TABLE `admission_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sorting_order` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `admission_sessions` (`id`, `name`, `sorting_order`, `created_at`, `updated_at`) VALUES
(9, '2005-2006', 0, '2020-07-22 17:00:48', '2020-07-22 17:00:48'),
(10, '2006-2007', 0, '2020-07-22 17:00:56', '2020-07-22 17:00:56'),
(11, '2007-2008', 0, '2020-07-22 17:01:03', '2020-07-22 17:01:03'),
(12, '2008-2009', 0, '2020-07-22 17:01:12', '2020-07-22 17:01:12'),
(13, '2009-2010', 0, '2020-07-22 17:01:21', '2020-07-22 17:01:21'),
(14, '2010-2011', 0, '2020-07-22 17:01:31', '2020-07-22 17:01:31'),
(15, '2011-2012', 0, '2020-07-22 17:01:44', '2020-07-22 17:01:44'),
(16, '2012-2013', 0, '2020-07-22 17:01:55', '2020-07-22 17:01:55'),
(17, '2013-2014', 0, '2020-07-22 17:02:04', '2020-07-22 17:02:04'),
(18, '2014-2015', 0, '2020-07-22 17:02:14', '2020-07-22 17:02:14'),
(19, '2015-2016', 0, '2020-07-22 17:02:22', '2020-07-22 17:02:22'),
(20, '2016-2017', 0, '2020-07-22 17:02:30', '2020-07-22 17:02:30'),
(21, '2017-2018', 0, '2020-07-22 17:02:42', '2020-07-22 17:02:42'),
(22, '2018-2019', 0, '2020-07-22 17:02:51', '2020-07-22 17:02:51'),
(23, '2019-2020', 0, '2020-07-22 17:03:01', '2020-07-22 17:03:01'),
(24, '2020-2021', 0, '2020-07-22 17:03:10', '2020-07-22 17:03:10');


ALTER TABLE `admission_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admission_sessions_name_unique` (`name`);


ALTER TABLE `admission_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

CREATE TABLE `global_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `global_statuses` (`id`, `model`, `name`, `created_at`, `updated_at`) VALUES
(1, 'App\\StudentEmailApply', 'Pending', '2020-07-24 14:28:06', '2020-07-24 14:28:06'),
(2, 'App\\StudentEmailApply', 'Completed', '2020-07-24 14:28:06', '2020-07-24 14:28:06'),
(3, 'App\\StudentEmailApply', 'Rejected', '2020-07-27 05:13:37', '2020-07-27 05:13:37');


ALTER TABLE `global_statuses`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `global_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`);


ALTER TABLE `telescope_entries_tags`
  ADD KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);


ALTER TABLE `telescope_entries`
  MODIFY `sequence` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;


  CREATE TABLE `students` (
  `id` bigint UNSIGNED NOT NULL,
  `admission_session_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roll` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `registration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hall` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_regestration_unique` (`registration`),
  ADD KEY `students_admission_session_id_foreign` (`admission_session_id`);


ALTER TABLE `students`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `students`
  ADD CONSTRAINT `students_admission_session_id_foreign` FOREIGN KEY (`admission_session_id`) REFERENCES `admission_sessions` (`id`) ON DELETE CASCADE;

ALTER TABLE `programs` ADD `slug` VARCHAR(255) NULL AFTER `name`;

ALTER TABLE `student_email_applies` DROP `name`;

ALTER TABLE `student_email_applies` ADD `first_name` VARCHAR(255) NULL AFTER `registration_number`, ADD `middle_name` VARCHAR(255) NULL AFTER `first_name`, ADD `last_name` VARCHAR(255) NULL AFTER `middle_name`;

CREATE TABLE `user_admission_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `admission_session_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `user_admission_sessions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_admission_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

alter table `student_email_applies` add `checked_at` timestamp null after `updated_at`, add `checked_by` varchar(255) null after `updated_by`

alter table `admission_sessions` add `is_verifyable` tinyint(1) not null default '0' after `sorting_order`

ALTER TABLE teacher_experiences CHANGE position position VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`

ALTER TABLE teacher_activities CHANGE position position VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`

ALTER TABLE teacher_experiences CHANGE organization organization VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`

ALTER TABLE teacher_activities CHANGE organization organization VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`

ALTER TABLE teacher_educations CHANGE institute institute VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`