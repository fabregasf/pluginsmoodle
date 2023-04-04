<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing HTML block instances.
 *
 * @package   block_html
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_cead_blockdefault extends block_base {

    function init() {
        //$this->title = get_string('pluginname', 'block_html');
        //$this->title = get_string(identifier: 'pluginname', component:'block_testblock');
        $this->title = "SUAS DISCIPLINAS:";
    }

    /*function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        if (isset($this->config->title)) {
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newhtmlblock', 'block_html');
        }
    }*/

    function instance_allow_multiple() {
        return false;
    }

    function get_content() {
        global $DB;
        global $USER;
        global $OUTPUT;

        if ($this->content !== NULL) {
            return $this->content;
        }

        // Ver mais sobre a Data manipulation API
        $userstring = '';
        $this->content = new stdClass;
        //$this->content->text = $userstring;
        //$this->content->footer = 'Texto de rodapé do plugin (bloco)';
        $this->content->items = array();
 
        $anchor = html_writer::tag('a', $USER->firstname . ' '. $USER->lastname, array('href' => 'http://www.globo.com'));
        $this->content->text = html_writer::div($anchor, '', array('id'=>$USER->id));

        $sqlEntrada =
        'SELECT c.id AS cid, c.fullname, c.shortname, u.id        
        FROM {user} u
        INNER JOIN {role_assignments} ra ON ra.userid = u.id
        INNER JOIN {context} ct ON ct.id = ra.contextid
        INNER JOIN {course} c ON c.id = ct.instanceid
        INNER JOIN {role} r ON r.id = ra.roleid
        WHERE u.id = ?';

        $saidaSQLDisciplinas = '<ul>';

        $registros = $DB->get_records_sql($sqlEntrada, array($USER->id), 0, 10);

        $dados = [];

        // usando a variável de ambiente $USER para ver os dados de nome e sobrenome
        $dados['nomeAluno'] = $USER->firstname . ' ' . $USER->lastname;

        // component, action, target, 
        foreach ($registros as $reg) {
            $url = "http://localhost/moodle/course/view.php?id={$reg->cid}";
            $saidaSQLDisciplinas .= "<li><a tabindex=\"0\" href=\"{$url}\">{$reg->fullname}   -   {$reg-> shortname}</a></li>";
        }

        $saidaSQLDisciplinas .= '</ul>';

        $sqlEntrada =
        'SELECT q.id AS qid, q.id, c.id, u.username AS username, c.shortname, q.id
        FROM {user} u
        INNER JOIN {role_assignments} ra ON ra.userid = u.id
        INNER JOIN {context} ct ON ct.id = ra.contextid
        INNER JOIN {course} c ON c.id = ct.instanceid
        INNER JOIN {quiz} q ON ct.instanceid = q.course
        INNER JOIN {role} r ON r.id = ra.roleid
        WHERE TO_TIMESTAMP(timeclose) >= NOW() AND u.id = ?';

        $registros = $DB->get_records_sql($sqlEntrada, array($USER->id), 0, 100);

        foreach ($registros as $reg) {
            $url = "http://localhost/moodle/course/view.php?id={$reg->qid}";
            $saidaSQLDisciplinas .= "<li><a tabindex=\"0\" href=\"{$url}\"><b>Disciplina {$qid}</a></li>";
        }

        $sqlEntradaEmailDuplicados = 'SELECT email FROM cead_emails_replicados';
        $registros = $DB->get_records_sql($sqlEntradaEmailDuplicados, array(), 0, 100);
        $saidaEmailsDuplicados = '';

        foreach ($registros as $reg) {
            $saidaEmailsDuplicados .= "<li>{$reg->email}</li>";
        }

        // Exibindo, ainda em JSON, o resultado de uma consulta SQL que contém um INNER JOIN
        $dados['disciplinas'] = $saidaSQLDisciplinas;
        $dados['atividades'] = $saidaSQLAtividades;
        $dados['emailsDuplicados'] = $saidaEmailsDuplicados;

        $this->content->items[] = html_writer::tag('p', 'Menu Option 1', array('id' => 'link'));

        // Renderizando o template e passando dados para ele
        $this->content->text = $OUTPUT->render_from_template('block_cead_blockdefault/bloco', $dados);
        //$this->content->text = "<p>Paragrafo</p>";

        return $this->content;
    }
    
}
