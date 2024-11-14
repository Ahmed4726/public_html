<?php

namespace App\Exports;

use App\Teacher;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Excel;

class TeachersExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;
    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'teachers.xlsx';

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
     * Count the export row
     *
     * @var integer
     */
    private $count = 1;

    /**
     * export heading
     *
     * @var array
     */
    private $headings = ['#'];

    /**
     * Queryable relations list
     *
     * @var array
     */
    private $relations;

    /**
     * Search by teacher name and email
     *
     * @var string
     */
    private $search;

    /**
     * Search by staus
     *
     * @var integer
     */
    private $status;

    /**
     * Search by deparmtment
     *
     * @var integer
     */
    private $department;

    /**
     * Search by designation
     *
     * @var integer
     */
    private $designation;

    /**
     * Expportable maximum result
     *
     * @var integer
     */
    private $maximumResult = 5000;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Teacher::allWithOptionalFilter($this->search, $this->status, $this->department, $this->designation, $this->maximumResult, $this->relations);
    }

    /**
     * Set search param
     *
     * @param array $headings
     * @return TeachersExport
     */
    public function search($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Set status id
     *
     * @param array $headings
     * @return TeachersExport
     */
    public function status($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set department id
     *
     * @param array $headings
     * @return TeachersExport
     */
    public function department($department)
    {
        $this->department = $department;
        return $this;
    }

    /**
     * Set designation id
     *
     * @param array $headings
     * @return TeachersExport
     */
    public function designation($designation)
    {
        $this->designation = $designation;
        return $this;
    }

    /**
     * Set heading lists
     *
     * @param array $headings
     * @return TeachersExport
     */
    public function withHeadings($headings)
    {
        array_push($this->headings, ...$headings);
        return $this;
    }

    /**
     * Set realtions lists
     *
     * @param array $relations
     * @return TeachersExport
     */
    public function withRelations($relations)
    {
        $this->relations = $relations;
        return $this;
    }

    /**
     * Heading list
     *
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
    * @var Teacher $teacher
    */
    public function map($teacher): array
    {
        $return = [$this->count++];
        !in_array('Name', $this->headings) ?: array_push($return, $teacher->name);
        !in_array('Email', $this->headings) ?: array_push($return, $teacher->email);
        !in_array('Additional Email', $this->headings) ?: array_push($return, $teacher->additional_emails);
        !in_array('Phone', $this->headings) ?: array_push($return, $teacher->cell_phone);
        !in_array('Status', $this->headings) ?: array_push($return, $teacher->statusInfo->name ?? 'Inactive');
        !in_array('Designation', $this->headings) ?: array_push($return, $teacher->designationInfo->name);
        !in_array('Department', $this->headings) ?: array_push($return, $teacher->department->name);
        !in_array('Image', $this->headings) ?: array_push($return, ($teacher->image_url ? 'Available' : 'Not Available'));

        return $return;
    }
}
