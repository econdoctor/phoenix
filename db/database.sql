SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `phoenix_answers` (
  `id` mediumint(9) NOT NULL,
  `user_id` smallint(6) NOT NULL,
  `question_id` smallint(6) NOT NULL,
  `answer` tinyint(4) NOT NULL,
  `answer_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `answer_type` tinyint(4) NOT NULL,
  `paper_id` mediumint(9) NOT NULL,
  `topic_id` smallint(6) NOT NULL,
  `assign_id` smallint(6) NOT NULL,
  `answer_valid` tinyint(4) NOT NULL,
  `answer_syllabus` tinyint(4) NOT NULL,
  `answer_points` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_assign` (
  `assign_id` smallint(6) NOT NULL,
  `assign_teacher` smallint(6) NOT NULL,
  `assign_active` tinyint(4) NOT NULL,
  `assign_created` datetime NOT NULL,
  `assign_start` datetime DEFAULT NULL,
  `assign_deadline` datetime NOT NULL,
  `assign_type` tinyint(4) NOT NULL,
  `assign_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `assign_time_allowed` smallint(6) NOT NULL,
  `assign_syllabus` tinyint(4) NOT NULL,
  `assign_order` tinyint(4) NOT NULL,
  `assign_scramble` tinyint(4) NOT NULL,
  `assign_pt` tinyint(4) NOT NULL,
  `assign_a` tinyint(4) NOT NULL,
  `assign_b` tinyint(4) NOT NULL,
  `assign_c` tinyint(4) NOT NULL,
  `assign_d` tinyint(4) NOT NULL,
  `assign_e` tinyint(4) NOT NULL,
  `assign_f` tinyint(4) NOT NULL,
  `assign_g` tinyint(4) NOT NULL,
  `assign_release` tinyint(4) NOT NULL,
  `assign_hide` tinyint(4) NOT NULL,
  `assign_nq` smallint(6) NOT NULL,
  `assign_time_limit` smallint(6) NOT NULL,
  `assign_game_status` tinyint(4) NOT NULL,
  `assign_teams` tinyint(4) NOT NULL,
  `assign_game_pause` tinyint(4) NOT NULL,
  `assign_game_remaining` smallint(6) DEFAULT NULL,
  `assign_show_ranking` tinyint(4) NOT NULL,
  `assign_key` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

CREATE TABLE `phoenix_assign_qp` (
  `id` mediumint(9) NOT NULL,
  `assign_id` smallint(6) NOT NULL,
  `paper_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_assign_questions` (
  `id` mediumint(9) NOT NULL,
  `assign_id` smallint(6) NOT NULL,
  `question_id` smallint(6) NOT NULL,
  `assign_question_rate` decimal(5,2) DEFAULT NULL,
  `assign_question_rate_a` decimal(5,2) DEFAULT NULL,
  `assign_question_rate_b` decimal(5,2) DEFAULT NULL,
  `assign_question_rate_c` decimal(5,2) DEFAULT NULL,
  `assign_question_rate_d` decimal(5,2) DEFAULT NULL,
  `assign_question_number` smallint(6) DEFAULT NULL,
  `assign_question_refresh` tinyint(4) NOT NULL,
  `assign_question_hide` tinyint(4) NOT NULL,
  `assign_question_status` tinyint(4) NOT NULL,
  `assign_question_deadline` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_assign_questions_originality` (
  `id` mediumint(9) NOT NULL,
  `question_id` smallint(6) DEFAULT NULL,
  `teacher_id` smallint(6) NOT NULL,
  `originality` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_assign_t` (
  `id` mediumint(9) NOT NULL,
  `assign_id` smallint(6) NOT NULL,
  `topic_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_assign_teams` (
  `id` smallint(6) NOT NULL,
  `assign_id` smallint(6) NOT NULL,
  `team` tinyint(4) NOT NULL,
  `team_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `team_shield` tinyint(4) NOT NULL,
  `members` tinyint(4) NOT NULL,
  `score` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

CREATE TABLE `phoenix_assign_users` (
  `id` mediumint(9) NOT NULL,
  `student_id` smallint(6) NOT NULL,
  `assign_id` smallint(6) NOT NULL,
  `student_refresh` tinyint(4) NOT NULL,
  `num_answers` tinyint(4) NOT NULL,
  `score` tinyint(4) NOT NULL,
  `student_rank` tinyint(4) DEFAULT NULL,
  `assign_student_start` datetime DEFAULT NULL,
  `assign_student_end` datetime DEFAULT NULL,
  `assign_student_keep` tinyint(4) NOT NULL,
  `assign_student_team` tinyint(4) NOT NULL,
  `assign_student_coeff` smallint(6) NOT NULL DEFAULT 100,
  `assign_student_points` smallint(6) NOT NULL,
  `assign_student_ping` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_groups` (
  `group_id` smallint(6) NOT NULL,
  `group_teacher` smallint(6) NOT NULL,
  `group_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `group_curriculum` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

CREATE TABLE `phoenix_group_users` (
  `id` mediumint(9) NOT NULL,
  `user_id` smallint(6) NOT NULL,
  `group_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_papers` (
  `paper_id` mediumint(9) NOT NULL,
  `paper_reference` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `paper_syllabus` tinyint(4) NOT NULL,
  `paper_year` smallint(6) NOT NULL,
  `paper_serie` tinyint(4) NOT NULL,
  `paper_version` tinyint(4) NOT NULL,
  `paper_a` tinyint(4) NOT NULL,
  `paper_b` tinyint(4) NOT NULL,
  `paper_c` tinyint(4) NOT NULL,
  `paper_d` tinyint(4) NOT NULL,
  `paper_e` tinyint(4) NOT NULL,
  `paper_f` tinyint(4) NOT NULL,
  `paper_g` tinyint(4) NOT NULL,
  `paper_hidden` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_permissions` (
  `permission_id` smallint(6) NOT NULL,
  `permission_teacher` smallint(6) NOT NULL,
  `permission_expire` datetime NOT NULL,
  `permission_syllabus` tinyint(4) NOT NULL,
  `permission_created` datetime NOT NULL,
  `permission_type` tinyint(4) NOT NULL,
  `permission_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_permissions_papers` (
  `id` smallint(6) NOT NULL,
  `permission_id` smallint(6) NOT NULL,
  `paper_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_permissions_topics` (
  `id` smallint(6) NOT NULL,
  `permission_id` smallint(6) NOT NULL,
  `topic_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_permissions_users` (
  `id` smallint(6) NOT NULL,
  `permission_id` smallint(6) NOT NULL,
  `student_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_questions` (
  `question_id` smallint(6) NOT NULL,
  `question_paper_id` mediumint(9) NOT NULL,
  `question_serie` tinyint(4) NOT NULL,
  `question_number` tinyint(4) NOT NULL,
  `question_answer` tinyint(4) NOT NULL,
  `question_syllabus` tinyint(4) NOT NULL,
  `question_new_syllabus` tinyint(4) NOT NULL,
  `question_unit` tinyint(4) NOT NULL,
  `question_module` tinyint(4) NOT NULL,
  `question_topic_id` smallint(6) NOT NULL,
  `question_rate_a` decimal(5,2) DEFAULT NULL,
  `question_rate_b` decimal(5,2) DEFAULT NULL,
  `question_rate_c` decimal(5,2) DEFAULT NULL,
  `question_rate_d` decimal(5,2) DEFAULT NULL,
  `question_rate` decimal(5,2) DEFAULT NULL,
  `question_random` int(11) NOT NULL,
  `question_repeat` tinyint(4) NOT NULL,
  `question_refresh` tinyint(4) NOT NULL,
  `question_change` tinyint(4) NOT NULL,
  `question_obsolete` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_thresholds` (
  `id` mediumint(9) NOT NULL,
  `teacher_id` smallint(6) NOT NULL,
  `syllabus` tinyint(4) NOT NULL,
  `min_a` tinyint(4) NOT NULL,
  `min_b` tinyint(4) NOT NULL,
  `min_c` tinyint(4) NOT NULL,
  `min_d` tinyint(4) NOT NULL,
  `min_e` tinyint(4) NOT NULL,
  `min_f` tinyint(4) NOT NULL,
  `min_g` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_timezones` (
  `id` tinyint(4) NOT NULL,
  `value` smallint(6) NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `phoenix_topics` (
  `topic_id` smallint(6) NOT NULL,
  `topic_syllabus` tinyint(4) NOT NULL,
  `topic_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `topic_module` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `topic_unit_id` tinyint(4) NOT NULL,
  `topic_module_id` tinyint(4) NOT NULL,
  `topic_hidden` tinyint(4) NOT NULL,
  `topic_key` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `phoenix_users` (
  `user_id` smallint(6) NOT NULL,
  `user_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_reg_date` date NOT NULL,
  `user_email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_first_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_last_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_type` tinyint(4) NOT NULL,
  `user_teacher` smallint(6) NOT NULL,
  `user_title` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_syllabus` tinyint(4) NOT NULL,
  `user_code` int(11) NOT NULL,
  `user_timezone` smallint(6) NOT NULL,
  `user_active` tinyint(4) NOT NULL,
  `user_last_login` datetime NOT NULL,
  `user_alias` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_score_igcse` decimal(5,2) NOT NULL,
  `user_score_as` decimal(5,2) NOT NULL,
  `user_score_a2` decimal(5,2) NOT NULL,
  `user_score_gen` decimal(5,2) NOT NULL,
  `user_refresh` tinyint(4) NOT NULL,
  `user_avatar` tinyint(4) NOT NULL,
  `user_recovery` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_archived` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

ALTER TABLE `phoenix_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `assign_id` (`assign_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `answer_valid` (`answer_valid`);

ALTER TABLE `phoenix_assign`
  ADD PRIMARY KEY (`assign_id`),
  ADD KEY `assign_id` (`assign_id`);

ALTER TABLE `phoenix_assign_qp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assign_id` (`assign_id`);

ALTER TABLE `phoenix_assign_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assign_id` (`assign_id`),
  ADD KEY `question_id` (`question_id`);

ALTER TABLE `phoenix_assign_questions_originality`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `teacher_id` (`teacher_id`);

ALTER TABLE `phoenix_assign_t`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assign_id` (`assign_id`);

ALTER TABLE `phoenix_assign_teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assign_id` (`assign_id`);

ALTER TABLE `phoenix_assign_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assign_id` (`assign_id`),
  ADD KEY `student_id` (`student_id`);

ALTER TABLE `phoenix_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `group_teacher` (`group_teacher`);

ALTER TABLE `phoenix_group_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

ALTER TABLE `phoenix_papers`
  ADD PRIMARY KEY (`paper_id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `paper_syllabus` (`paper_syllabus`);

ALTER TABLE `phoenix_permissions`
  ADD PRIMARY KEY (`permission_id`);

ALTER TABLE `phoenix_permissions_papers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_id` (`permission_id`);

ALTER TABLE `phoenix_permissions_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_id` (`permission_id`);

ALTER TABLE `phoenix_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_id` (`permission_id`);

ALTER TABLE `phoenix_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `question_topic_id` (`question_topic_id`),
  ADD KEY `question_repeat` (`question_repeat`),
  ADD KEY `question_paper_id` (`question_paper_id`);

ALTER TABLE `phoenix_thresholds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `syllabus` (`syllabus`);

ALTER TABLE `phoenix_timezones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `value` (`value`);

ALTER TABLE `phoenix_topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `topic_id` (`topic_id`);

ALTER TABLE `phoenix_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_teacher` (`user_teacher`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `user_syllabus` (`user_syllabus`);

ALTER TABLE `phoenix_answers`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_assign`
  MODIFY `assign_id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_assign_qp`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_assign_questions`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_assign_questions_originality`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_assign_t`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_assign_teams`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_assign_users`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_groups`
  MODIFY `group_id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_group_users`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_permissions`
  MODIFY `permission_id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_permissions_papers`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_permissions_topics`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_permissions_users`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_questions`
  MODIFY `question_id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_thresholds`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_timezones`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_topics`
  MODIFY `topic_id` smallint(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE `phoenix_users`
  MODIFY `user_id` smallint(6) NOT NULL AUTO_INCREMENT;
COMMIT;