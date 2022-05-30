<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Benchmark_test;
use App\Models\Chapter;
use App\Models\Cms_sub_category;
use App\Models\Concept;
use App\Models\Content;
use App\Models\Evaluation_test;
use App\Models\Fun_facts_sub_category;
use App\Models\Issue;
use App\Models\Mentor_sub_category;
use App\Models\Mentor_topic;
use App\Models\Motivation_cms_sub_category;
use App\Models\Package_price;
use App\Models\Package_strategy;
use App\Models\State;
use App\Models\Student;
use App\Models\Subject_grade;
use App\Models\Sub_category;
use App\Models\Sub_topic;
use App\Models\Teacher;
use App\Models\Topic;
use App\Models\Trouble_shooter_level_2;
use App\Models\Trouble_shooter_level_3;
use App\Models\Trouble_shooter_level_4;
use App\Models\Trouble_shooter_level_5;
use App\Models\User;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getStates(Request $request)
    {
        $country_id = $request->input('country_id');

        $get_states = State::where(['country_id' => $country_id, 'status' => '1'])->orderBy('state_name', 'asc')->get();

        $output = '<option value="">Select State</option>';
        foreach ($get_states as $get_state) {
            $output .= '<option value="' . $get_state->id . '">' . $get_state->state_name . '</option>';
        }
        return $output;
    }

    public function getSubjectGrades(Request $request)
    {
        $subject_id = $request->input('subject_id');

        $get_subject_grades = Subject_grade::where(['subject_id' => $subject_id, 'status' => '1'])->orderBy('subject_grade_name', 'asc')->get();

        $output = '<option value="">Select Subject Grade</option>';
        foreach ($get_subject_grades as $get_subject_grade) {
            $output .= '<option value="' . $get_subject_grade->id . '">' . $get_subject_grade->subject_grade_name . '</option>';
        }
        return $output;
    }

    public function getChapters(Request $request)
    {
        $subject_grade_id = $request->input('subject_grade_id');

        $get_chapters = Chapter::where(['subject_grade_id' => $subject_grade_id, 'status' => '1'])->orderBy('chapter_name', 'asc')->get();

        $output = '<option value="">Select Chapter</option>';
        foreach ($get_chapters as $get_chapter) {
            $output .= '<option value="' . $get_chapter->id . '">' . $get_chapter->chapter_name . '</option>';
        }
        return $output;
    }

    public function getConcepts(Request $request)
    {
        $chapter_id = $request->input('chapter_id');

        $get_concepts = Concept::where(['chapter_id' => $chapter_id, 'status' => '1'])->orderBy('concept_name', 'asc')->get();

        $output = '<option value="">Select Concept</option>';
        foreach ($get_concepts as $get_concept) {
            $output .= '<option value="' . $get_concept->id . '">' . $get_concept->concept_name . '</option>';
        }
        return $output;
    }

    public function getAdmins(Request $request)
    {
        $institute_id = $request->input('institute_id');

        $get_admins = User::where('institute_id',$institute_id)
                    ->where('status', '1')
                    ->where('group_id' , '2')
                    ->orderBy('name', 'asc')
                    ->get();
        $output = '<option value="">Select Admins</option>';
        foreach ($get_admins as $get_admin) {
            $output .= '<option value="' . $get_admin->id . '">' . $get_admin->name . '</option>';
        }
        return $output;
    }

    public function getTeachers(Request $request)
    {
        $admin_id = $request->input('admin_id');

        $get_teachers = User::where('admin_id',$admin_id)
                    ->where('status', '1')
                    ->where('group_id' , '3')
                    ->orderBy('name', 'asc')
                    ->get();

        $output = '<option value="">Select Teacher</option>';
        foreach ($get_teachers as $get_teacher) {
            $output .= '<option value="' . $get_teacher->id . '">' . $get_teacher->name . '</option>';
        }
        return $output;
    }

    public function getSubCategorys(Request $request)
    {
        $category_id = $request->input('category_id');

        $get_subcategorys = Sub_category::where(['category_id' => $category_id, 'status' => '1'])->orderBy('subcategory_name', 'asc')->get();

        $output = '<option value="">Select Sub category</option>';
        foreach ($get_subcategorys as $get_subcategory) {
            $output .= '<option value="' . $get_subcategory->id . '">' . $get_subcategory->subcategory_name . '</option>';
        }
        return $output;
    }

    public function getContents(Request $request)
    {
        $subcategory_id = $request->input('subcategory_id');
        $id = $request->input('id');

        $content_ids = [];
        $package_strategy = Package_strategy::where('id','!=',$id)->pluck('content_id')->toArray();
        foreach ($package_strategy as $package) {
            $content_ids = array_merge($content_ids, explode(',',$package));
        }

        $get_contents = Content::where(['subcategory_id' => $subcategory_id, 'status' => '1'])->whereNotIn('id', $content_ids)->orderBy('content_name', 'asc')->get();

        $output = '<option value="">Select Content</option>';
        foreach ($get_contents as $get_content) {
            $output .= '<option value="' . $get_content->id . '">' . $get_content->content_name . '</option>';
        }
        return $output;
    }

    public function getMentorSubCategorys(Request $request)
    {
        $mentor_category_id = $request->input('mentor_category_id');

        $get_mentor_sub_categorys = Mentor_sub_category::where(['mentor_category_id' => $mentor_category_id, 'status' => '1'])->orderBy('mentor_sub_category_name', 'asc')->get();

        $output = '<option value="">Select Mentor Sub category</option>';
        foreach ($get_mentor_sub_categorys as $sub_category) {
            $output .= '<option value="' . $sub_category->id . '">' . $sub_category->mentor_sub_category_name . '</option>';
        }
        return $output;
    }

    public function getMentorTopics(Request $request)
    {
        $mentor_sub_category_id = $request->input('mentor_sub_category_id');

        $get_mentor_topics = Mentor_topic::where(['mentor_sub_category_id' => $mentor_sub_category_id, 'status' => '1'])->orderBy('mentor_topic', 'asc')->get();

        $output = '<option value="">Select Mentor Topic</option>';
        foreach ($get_mentor_topics as $mentor_topic) {
            $output .= '<option value="' . $mentor_topic->id . '">' . $mentor_topic->mentor_topic . '</option>';
        }
        return $output;
    }

    public function getTeacher(Request $request)
    {
        $institute_id = $request->input('institute_id');

        $get_teachers = User::where(['institute_id' => $institute_id, 'status' => '1', 'group_id' => '3'])->orderBy('name', 'asc')->get();

        $output = '<option value="">Select Teacher</option>';
        foreach ($get_teachers as $get_teacher) {
            $output .= '<option value="' . $get_teacher->teacher->id . '">' . $get_teacher->name . '</option>';
        }
        return $output;
    }

    public function getInTeacher(Request $request)
    {
        $institute_ids = $request->input('institute_ids');

        $get_teachers = User::where(['institute_id' => $institute_ids, 'status' => '1', 'group_id' => '3'])->orderBy('name', 'asc')->get();

        $output = '<option value="">Select Teacher</option>';
        foreach ($get_teachers as $get_teacher) {
            $output .= '<option value="' . $get_teacher->id . '">' . $get_teacher->name . '</option>';
        }
        return $output;
    }

    public function getSubjectGrade(Request $request)
    {
        $teacher_id = $request->input('teacher_id');

        $teacher = Teacher::where('id',$teacher_id)->first();

        $get_subject_grades = Subject_grade::where('status' , '1')
            ->whereIn('id',explode(',',$teacher->subject_grade_id))
            ->orderBy('subject_grade_name', 'asc')
            ->get();

        $output = '<option value="">Select Subject Grade</option>';
        foreach ($get_subject_grades as $get_subject_grade) {
            $output .= '<option value="' . $get_subject_grade->id . '">' . $get_subject_grade->subject_grade_name . '</option>';
        }
        return $output;
    }

    public function getStudent(Request $request)
    {
        $teacher_ids = $request->input('teacher_ids');
        $teacher = Teacher::where('user_id',$teacher_ids)->first();
        $students = Student::where('teacher_id',$teacher->id)
            ->orderBy('id', 'asc')
            ->get();

        $output = '<option value="">Select Student</option>';
        foreach ($students as $student) {
            $output .= '<option value="' . $student->user_id . '">' . $student->user->name . '</option>';
        }
        return $output;
    }

    public function getBenchmarkTest(Request $request)
    {
        $institute_id = $request->input('institute_id');

        $get_benchmark_tests = Benchmark_test::where(['institute_id' => $institute_id, 'status' => '1'])->orderBy('name', 'asc')->get();

        $output = '<option value="">Select Test</option>';
        foreach ($get_benchmark_tests as $get_benchmark_test) {
            $output .= '<option value="' . $get_benchmark_test->id . '">' . $get_benchmark_test->name . '</option>';
        }
        return $output;
    }

    public function getEvaluationTest(Request $request)
    {
        $institute_id = $request->input('institute_id');

        $get_evaluation_tests = Evaluation_test::where(['institute_id' => $institute_id, 'status' => '1'])->orderBy('name', 'asc')->get();

        $output = '<option value="">Select Test</option>';
        foreach ($get_evaluation_tests as $get_evaluation_test) {
            $output .= '<option value="' . $get_evaluation_test->id . '">' . $get_evaluation_test->name . '</option>';
        }
        return $output;
    }

    public function getTopics(Request $request)
    {
        $subject_id = $request->input('subject_id');

        $get_topics = Topic::where(['subject_id' => $subject_id, 'status' => '1'])->orderBy('topic_name', 'asc')->get();

        $output = '<option value="">Select Topic</option>';
        foreach ($get_topics as $get_topic) {
            $output .= '<option value="' . $get_topic->id . '">' . $get_topic->topic_name . '</option>';
        }
        return $output;
    }

    public function getSubTopics(Request $request)
    {
        $topic_id = $request->input('topic_id');

        $get_sub_topics = Sub_topic::where(['topic_id' => $topic_id, 'status' => '1'])->orderBy('sub_topic_name', 'asc')->get();

        $output = '<option value="">Select Sub Topic</option>';
        foreach ($get_sub_topics as $get_sub_topic) {
            $output .= '<option value="' . $get_sub_topic->id . '">' . $get_sub_topic->sub_topic_name . '</option>';
        }
        return $output;
    }

    public function getIssues(Request $request)
    {
        $sub_topic_id = $request->input('sub_topic_id');

        $get_issues = Issue::where(['sub_topic_id' => $sub_topic_id, 'status' => '1'])->orderBy('background', 'asc')->get();

        $output = '<option value="">Select Issue</option>';
        foreach ($get_issues as $get_issue) {
            $output .= '<option value="' . $get_issue->id . '">' . $get_issue->background . '</option>';
        }
        return $output;
    }

    public function getLevel2(Request $request)
    {
        $trouble_shooter_level_1_id = $request->input('trouble_shooter_level_1_id');

        $levels2 = Trouble_shooter_level_2::where(['trouble_shooter_level_1_id' => $trouble_shooter_level_1_id, 'status' => '1'])->orderBy('trouble_shooter_name2', 'asc')->get();

        $output = '<option value="">Select Level 2</option>';
        foreach ($levels2 as $level2) {
            $output .= '<option value="' . $level2->id . '">' . $level2->trouble_shooter_name2 . '</option>';
        }
        return $output;
    }

    public function getLevel3(Request $request)
    {
        $trouble_shooter_level_2_id = $request->input('trouble_shooter_level_2_id');

        $levels3 = Trouble_shooter_level_3::where(['trouble_shooter_level_2_id' => $trouble_shooter_level_2_id, 'status' => '1'])->orderBy('trouble_shooter_name3', 'asc')->get();

        $output = '<option value="">Select Level 3</option>';
        foreach ($levels3 as $level3) {
            $output .= '<option value="' . $level3->id . '">' . $level3->trouble_shooter_name3 . '</option>';
        }
        return $output;
    }

    public function getLevel4(Request $request)
    {
        $trouble_shooter_level_3_id = $request->input('trouble_shooter_level_3_id');

        $levels4 = Trouble_shooter_level_4::where(['trouble_shooter_level_3_id' => $trouble_shooter_level_3_id, 'status' => '1'])->orderBy('trouble_shooter_name4', 'asc')->get();

        $output = '<option value="">Select Level 4</option>';
        foreach ($levels4 as $level4) {
            $output .= '<option value="' . $level4->id . '">' . $level4->trouble_shooter_name4 . '</option>';
        }
        return $output;
    }

    public function getLevel5(Request $request)
    {
        $trouble_shooter_level_4_id = $request->input('trouble_shooter_level_4_id');

        $levels5 = Trouble_shooter_level_5::where(['trouble_shooter_level_4_id' => $trouble_shooter_level_4_id, 'status' => '1'])->orderBy('trouble_shooter_name5', 'asc')->get();

        $output = '<option value="">Select Level 5</option>';
        foreach ($levels5 as $level5) {
            $output .= '<option value="' . $level5->id . '">' . $level5->trouble_shooter_name5 . '</option>';
        }
        return $output;
    }

    public function getPackageModules(Request $request)
    {
        $package_id = $request->input('package_id');

        $get_package_module = Package_price::where(['package_id' => $package_id])->orderBy('package_module_id', 'asc')->get();

        $output = '<option value="">Select Package Module</option>';
        foreach ($get_package_module as $get_module) {
            $output .= '<option value="' . $get_module->id . '">' . $get_module->package_module->package_module_name . '</option>';
        }
        return $output;
    }

    public function getFunSubCategorys(Request $request)
    {
        $fun_facts_category_id = $request->input('fun_facts_category_id');

        $fun_facts_sub_categories = Fun_facts_sub_category::where(['fun_facts_category_id' => $fun_facts_category_id, 'status' => '1'])->orderBy('fun_sub_category_name', 'asc')->get();

        $output = '<option value="">Select Sub category</option>';
        foreach ($fun_facts_sub_categories as $fun_facts_sub_category) {
            $output .= '<option value="' . $fun_facts_sub_category->id . '">' . $fun_facts_sub_category->fun_sub_category_name . '</option>';
        }
        return $output;
    }

    public function getCmsSubCategorys(Request $request)
    {
        $motivation_cms_category_id = $request->input('motivation_cms_category_id');

        $motivation_cms_sub_categories = Motivation_cms_sub_category::where(['motivation_cms_category_id' => $motivation_cms_category_id, 'status' => '1'])->orderBy('motivation_cms_sub_category_name', 'asc')->get();

        $output = '<option value="">Select Sub category</option>';
        foreach ($motivation_cms_sub_categories as $motivation_cms_sub_category) {
            $output .= '<option value="' . $motivation_cms_sub_category->id . '">' . $motivation_cms_sub_category->motivation_cms_sub_category_name . '</option>';
        }
        return $output;
    }

    public function getTeacherByInstitute(Request $request)
    {
        $institute_id = $request->input('institute_id');

        $get_teachers = User::whereIn('institute_id',$institute_id)
                    ->where('status', '1')
                    ->where('group_id' , '3')
                    ->orderBy('name', 'asc')
                    ->get();

        $output = '<option value="">Select Teacher</option>';
        foreach ($get_teachers as $get_teacher) {
            $output .= '<option value="' . $get_teacher->teacher->id . '">' . $get_teacher->name . '</option>';
        }
        return $output;
    }

    public function getUserByInstitute(Request $request)
    {
        $institute_id = $request->input('institute_id');

        $get_users = User::whereIn('institute_id',$institute_id)
                    ->where('status', '1')
                    ->where('group_id' , '4')
                    ->orderBy('name', 'asc')
                    ->get();
                    
        $output = '<option value="">Select User</option>';
        foreach ($get_users as $get_user) 
        {
            $output .= '<option value="' . $get_user->student->id . '">' . $get_user->name . '</option>';
        }
        return $output;
    }
}
