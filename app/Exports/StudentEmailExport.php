<?php

namespace App\Exports;

use App\StudentEmailApply;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class StudentEmailExport implements FromCollection, Responsable, WithMapping, WithHeadings
{
    use Exportable;

    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'studentEmail.xlsx';

    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;

    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    /**
     * Required with relation
     *
     * @var array
     */
    private $with = ['admissionSession', 'program', 'department', 'hall', 'globalStatus'];

    /**
     * Columns Array For Export
     *
     * @var array
     */
    private $columns = ['*'];

    /**
     * Initialize colums
     *
     * @param array $columns
     * @return void
     */
    public function onlyColumns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Resposne collection to export
     *
     * @return void
     */
    public function collection()
    {
        return StudentEmailApply::allWithOptionalFilter($this->with, 10000);
    }

    /**
     * Heading of export
     *
     * @return array
     */
    public function headings(): array
    {
        if (current($this->columns) != '*') {
            return $this->columns;
        }

        return [];
    }

    /**
     * Processed data
     *
     * @param [type] $email
     * @return array
     */
    public function map($email): array
    {
        if (current($this->columns) == '*') {
            return $email->attributesToArray();
        } else {
            $return = [
                (in_array('Admission Session', $this->columns)) ? $email->admissionSession->name : 'not found',
                (in_array('Registration Number', $this->columns)) ? $email->registration_number : 'not found',
                (in_array('Department', $this->columns)) ? $email->department->name : 'not found',
                (in_array('Program', $this->columns)) ? $email->program->name : 'not found',
                (in_array('Hall', $this->columns)) ? $email->hall->name : 'not found',
                (in_array('First Name', $this->columns)) ? $email->first_name : 'not found',
                (in_array('Middle Name', $this->columns)) ? $email->middle_name : 'not found',
                (in_array('Last Name', $this->columns) && in_array('Middle Name', $this->columns)) ? $email->last_name : 'not found',
                (in_array('Last Name', $this->columns) && !in_array('Middle Name', $this->columns)) ? implode(' ', array_filter([$email->middle_name, $email->last_name])) : 'not found',
                (in_array('Email', $this->columns)) ? $email->username . StudentEmailApply::$emailDomain : 'not found',
                (in_array('Password', $this->columns)) ? $email->password : 'not found',
                (in_array('Existing Email', $this->columns)) ? $email->existing_email : 'not found',
                (in_array('Contact Phone', $this->columns)) ? $email->contact_phone : 'not found',
                (in_array('Status', $this->columns)) ? $email->globalStatus->name : 'not found',
                (in_array('Applied At', $this->columns)) ? $email->created_at->format('Y-m-d h:i:s a') : 'not found',
                (in_array('Updated At', $this->columns)) ? $email->updated_at->format('Y-m-d h:i:s a') : 'not found',
                (in_array('Updated By', $this->columns)) ? $email->updatedBy->name : 'not found',
                (in_array('Comment', $this->columns)) ? $email->comment : 'not found',
            ];
            return array_filter($return, fn ($data) => $data != 'not found');
        }
    }
}
