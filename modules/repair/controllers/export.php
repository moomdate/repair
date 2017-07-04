<?php
/**
 * @filesource modules/repair/controllers/export.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Export;

use \Kotchasan\Http\Request;
use \Gcms\Login;

/**
 * Controller สำหรับแสดงหน้าเว็บ
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * หน้าสำหรับพิมพ์ (print.html)
   *
   * @param Request $request
   */
  public function index(Request $request)
  {
    // ตรวจสอบ id ที่ต้องการ
    if (preg_match('/([A-Z0-9]{10,10})/', $request->get('id')->toString(), $match)) {
      // session cookie
      $request->initSession();
      // can_received_repair, can_repair
      if (Login::checkPermission(Login::isMember(), array('can_received_repair', 'can_repair'))) {
        // อ่านข้อมูลการทำรายการ
        $index = \Repair\Export\Model::get($match[1]);
        if ($index) {
          $detail = createClass('Repair\Export\View')->render($index);
        }
      }
    }
    if (empty($detail)) {
      // ไม่พบโมดูลหรือไม่มีสิทธิ
      new \Kotchasan\Http\NotFound();
    } else {
      // แสดงผล
      echo $detail;
    }
  }
}
