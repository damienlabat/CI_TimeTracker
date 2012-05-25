<?php
if ( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Records extends CI_Model {
    private $activities_table = 'activities';
    private $categories_table = 'categories';
    private $records_table = 'records';



    /**
     * Get record by Id
     *
     * @record_id     int
     * @return          array
     */
    function get_record_by_id( $record_id ) {
        $this->db->where( 'id', $record_id );

        $query = $this->db->get( $this->records_table );


        /*  $this->db->select($this->activities_table.'.title,type_of_record,categorie_ID,'.$this->records_table.'.*');
        $this->db->from($this->records_table);
        $this->db->join($this->activities_table, $this->activities_table.'.id = '. $this->records_table.'.activity_ID');
        $this->db->where($this->records_table.'.id',$record_id);

        $query = $this->db->get();*/

        if ( $query->num_rows() == 1 )
            return $query->row_array();
        return NULL;
    }



    /**
     * Create new record
     *
     * @activity_id     int
     * @param           string
     */
    function create_record( $activity_id, $param ) {
        $param = array_merge( $param, array(
             'activity_ID' => $activity_id
        ) );

        if ( $this->db->insert( $this->records_table, $param ) ) {
            $data = $this->get_record_by_id( $this->db->insert_id() );
            return $data;
        }
        return NULL;
    }



    /**
     * Get running activity records
     *
     * @user_id     int
     * @return      array
     */
    function get_running_activities( $user_id, $categorie_id = NULL, $activity_id = NULL ) {
        $query = $this->db->query( 'SELECT ' . $this->activities_table . '.title,type_of_record,categorie_ID,' . $this->records_table . '.*
             FROM ' . $this->records_table . '
             LEFT JOIN ' . $this->activities_table . '
                ON ' . $this->records_table . '.activity_ID=' . $this->activities_table . '.id
             LEFT JOIN ' . $this->categories_table . '
                ON ' . $this->activities_table . '.categorie_ID=' . $this->categories_table . '.id
            WHERE
                user_ID=' . $user_id . '
                AND running=1
                AND type_of_record=\'activity\'
            ORDER BY start_time DESC' );

        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }


    /**
     * Get running todo records
     *
     * @user_id     int
     * @return          array
     */
    function get_running_TODO( $user_id , $categorie_id = NULL, $activity_id = NULL ) {
        $query = $this->db->query( 'SELECT ' . $this->activities_table . '.title,type_of_record,categorie_ID,' . $this->records_table . '.*
             FROM ' . $this->records_table . '
             LEFT JOIN ' . $this->activities_table . '
                ON ' . $this->records_table . '.activity_ID=' . $this->activities_table . '.id
             LEFT JOIN ' . $this->categories_table . '
                ON ' . $this->activities_table . '.categorie_ID=' . $this->categories_table . '.id
            WHERE
                user_ID=' . $user_id . '
                AND running=1
                AND type_of_record=\'todo\'
            ORDER BY start_time DESC' );

        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }




    /**
     * Get last records
     *
     * @user_id     int
     * @offset          int
     * @count           int
     * @return          array
     */
    function get_last_activities( $user_id, $categorie_id = NULL, $activity_id = NULL, $offset, $count ) {
        $query = $this->db->query( 'SELECT ' . $this->activities_table . '.title,type_of_record,categorie_ID,' . $this->records_table . '.*
             FROM ' . $this->records_table . '
             LEFT JOIN ' . $this->activities_table . '
                ON ' . $this->records_table . '.activity_ID=' . $this->activities_table . '.id
             LEFT JOIN ' . $this->categories_table . '
                ON ' . $this->activities_table . '.categorie_ID=' . $this->categories_table . '.id
            WHERE
                user_ID=' . $user_id . '
                AND running=0

            ORDER BY UNIX_TIMESTAMP(start_time)+duration DESC
            LIMIT ' . $offset . ',' . $count );

        if ( $query->num_rows() >= 1 )
            return $query->result_array();
        return NULL;
    }


    /**
     * Get last records count
     *
     * @user_id     int
     * @return      int
     */
     function get_last_records_count( $user_id, $categorie_id = NULL, $activity_id = NULL ) {

        $query = $this->db->query( 'SELECT count(' . $this->records_table . '.id) as count
             FROM ' . $this->records_table . '
             LEFT JOIN ' . $this->activities_table . '
                ON ' . $this->records_table . '.activity_ID=' . $this->activities_table . '.id
             LEFT JOIN ' . $this->categories_table . '
                ON ' . $this->activities_table . '.categorie_ID=' . $this->categories_table . '.id
            WHERE
                user_ID=' . $user_id . '
                AND running=0');

        return $query->row()->count;
    }


    /**
     * Update record
     *
     * @record_id       int
     * @title           string
     * @return          boolean
     */
    function update_record( $record_id, $param ) {
        $this->db->where( 'id', $record_id );

        if ( $this->db->update( $this->records_table, $param ) )
            return TRUE;

        return FALSE;
    }




    /**
     * delete record
     *
     * @record_id       int
     * @return          boolean
     */
    function delete_record( $record_id ) {
        $this->db->where( 'id', $record_id );

        if ( $this->db->delete( $this->records_table ) )
            return TRUE;

        return FALSE;
    }



    /* ===========
     * TOOLS
     * ===========*/


    function get_running_activities_full( $user_id ) {
        $activities = $this->get_running_activities( $user_id );
        if ( $activities )
            $activities = $this->complete_records_info( $activities );

        return $activities;
    }


    function get_running_TODO_full( $user_id ) {
        $activities = $this->get_running_TODO( $user_id );
        if ( $activities )
            $activities = $this->complete_records_info( $activities );

        return $activities;
    }


    function get_record_by_id_full( $record_id ) {
        $activitie = $this->get_record_by_id( $record_id );
        if ( $activitie )
            $activitie = $this->complete_record_info( $activitie );

        return $activitie;
    }


    function get_last_actions_full( $user_id, $categorie_id = NULL, $activity_id = NULL, $offset = 0, $count = 10 ) {

        $records = $this->get_last_activities( $user_id, $categorie_id, $activity_id, $offset, $count );

        if ( $records )
            $records = $this->complete_records_info( $records );

        return $records;
    }


    function restart_record( $record_id ) {
        $record = $this->get_record_by_id( $record_id );
        $param  = array(
             'description' => $record[ 'description' ],
            'diff_greenwich' => $record[ 'diff_greenwich' ]
        );

        if ( ( $record[ 'type_of_record' ] == 'value' ) || ( ( !$record[ 'running' ] ) && ( $record[ 'duration' ] == 0 ) ) )
            $param[ 'running' ] = 0;

        $new_record = $this->create_record( $record[ 'activity_ID' ], $param );

        foreach ( $record[ 'tags' ] as $k => $tag )
            $this->tags->add_tag( $new_record[ 'id' ], $tag[ 'id' ] ); // add tags

        if ( $record[ 'type_of_record' ] == 'value' )
            $this->values->add_value( $new_record[ 'id' ], $record[ 'value' ][ 'value_type_ID' ], $record[ 'value' ][ 'value' ] );

        return TRUE;
    }


    function stop_record( $id ) {
        $record   = $this->get_record_by_id( $id );
        $duration = $this->calcul_duration( $record );
        return $this->update_record( $id, array(
             'duration' => $duration,
            'running' => 0
        ) );
    }


    function calcul_duration( $record, $endtime = NULL ) {
        if ( $endtime == NULL )
            $endtime = time();
        $duration = $endtime - strtotime( $record[ 'start_time' ] );
        return $duration;
    }



    function complete_records_info( $records ) {
        foreach ( $records as $k => $record )
            $records[ $k ] = $this->complete_record_info( $record );

        return $records;
    }




    function complete_record_info( $record ) {

        if ( $record[ 'running' ] ){
            $record[ 'duration' ] = $this->calcul_duration( $record );
            $record[ 'stop_at' ] = NULL;
        }
        else
            $record[ 'stop_at' ] = date( "Y-m-d H:i:s", strtotime( $record[ 'start_time' ] ) + $record[ 'duration' ] );


        $record[ 'tags' ]  = $this->tags->get_record_tags( $record[ 'id' ] );
        $record[ 'value' ] = $this->values->get_record_value( $record[ 'id' ] );

        $record[ 'tag_path' ] = '';
        if ( $record[ 'tags' ] )
            foreach ( $record[ 'tags' ] as $k => $tag ) {
                if ( $record[ 'tag_path' ] != '' )
                    $record[ 'tag_path' ] .= ', ';
                $record[ 'tag_path' ] .= $tag[ 'tag' ];
            }

        $record[ 'activity' ] = $this->activities->get_activity_by_id_full( $record[ 'activity_ID' ] );

        return $record;
    }


} // END Class
