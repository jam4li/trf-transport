<?php

namespace Trust\INC\DateUtils;

function gDate($gy, $gm, $gd) {
  $gy = \intval($gy);
  $gm = \intval($gm);
  $gd = \intval($gd);

  while ($gd > 30) {
    $gm += 1;
    $gd -= 30;
  }

  while ($gm > 12) {
    $gy += 1;
    $gm -= 12;
  }

  if ($gd <= 9) {
    $gd = '0' . $gd;
  }

  if ($gm <= 9) {
    $gm = '0' . $gm;
  }

  return $gy . '-' . $gm . '-' . $gd;
}
