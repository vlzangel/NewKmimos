<?php
    /* CITAS */
    function new_cita($_POST_) {
        global $wpdb;
        extract( $_POST_ );
        $user_id = $_POST_['user_id'];
        unset( $_POST_['user_id'] );
        $data = json_encode($_POST_, JSON_UNESCAPED_UNICODE);
        $sql = "INSERT INTO wp_kmivet_reservas VALUES(
            NULL,
            '{$user_id}',
            NULL,
            '{$data}',
            0,
            0,
            '',
            '',
            NOW()
        )";
        if( $wpdb->query( $sql ) ){
            return $wpdb->insert_id;
        }else{
            return 0;
        }
    }

    /* PACIENTES */

    function new_paciente($user_id, $METAS) {
        global $wpdb;
        $data = json_encode($METAS, JSON_UNESCAPED_UNICODE);
        $sql = "INSERT INTO wp_kmivet_pacientes VALUES (
            NULL,
            '{$user_id}',
            '{$data}',
            NOW()
        )";
        if( $wpdb->query( $sql ) ){
            return $wpdb->insert_id;
        }else{
            return 0;
        }
    }
?>