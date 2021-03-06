<?php
// 今日头条辅助
// 使用 adb 模拟人工操作
// 设备 OnePlus 7 pro
// 屏幕尺寸 1440 * 3120
// 其他设备根据实际屏幕修改坐标

function start_app() {
    exec("adb shell am start -n com.ss.android.article.lite/com.ss.android.article.lite.activity.SplashActivity");
}

function stop_app() {
    exec("adb shell am force-stop com.ss.android.article.lite");
}

function swipe_up() {
    $ax = random_int(500, 800);
    $ay = random_int(2300, 2600);
    swipe([$ax, $ay],[random_int($ax-150, $ax+150), random_int($ay-500, $ay-250)]);
}

function swipe_down() {
    $ax = random_int(500, 800);
    $ay = random_int(2300, 2600);
    swipe([$ax, $ay],[random_int($ax-150, $ax+150), random_int($ay+250, $ay+550)]);
}

function swipe($a, $b) {
    $command = "adb shell input swipe $a[0] $a[1] $b[0] $b[1] ". random_int(100, 300);
    exec($command);
    wlog($command);
}

function tap($x, $y) {
    exec("adb shell input tap $x $y");
    wlog("tap $x $y");
}

/**
 * 领取奖励
 */
function rewards() {
    go_nav_bar(2);
    sleep(3);
    tap(random_int(1100, 1350), random_int(2250, 2800));
    sleep(2);

    tap(random_int(300,1200),random_int(1870, 2070));
    sleep(20);
    wlog("rewards");
    back();
    back();
}

/**
 * 返回
 */
function back() {
    exec("adb shell input keyevent 4");
}

/**
 * 点击底部导航
 */
function go_nav_bar($index = 0) {

    $nav_bar = [
        [
            [50,288],
            [2950, 3090]
        ],
        [
            [300,576],
            [2950, 3090]
        ],
        [
            [600, 850],
            [2950, 3090]
        ],
        [
            [900,1152],
            [2950, 3090]
        ],
        [
            [1200,1400],
            [2950, 3090]
        ],
        
    ];

    $x = random_int($nav_bar[$index][0][0], $nav_bar[$index][0][1]);
    $y = random_int($nav_bar[$index][1][0], $nav_bar[$index][1][1]);
    
    tap($x, $y);
}

function fake_read() {
    $swipe_times = random_int(3, 10);

    while($swipe_times--) {
        swipe_up();
        sleep(random_int(3, 10));
    }
}

function dy_rewards() {
    return;
    exec("adb shell am start -n com.ss.android.ugc.aweme.lite/com.ss.android.ugc.aweme.splash.SplashActivity");
    sleep(5);
    tap(700,2900);
    sleep(3);
    tap(1300, 2800);
    sleep(2);
    exec("adb shell am force-stop com.ss.android.ugc.aweme.lite");
    wlog("dy_rewards");
}

function wlog($log) {
    echo date("h:i:s") . "\t" .  $log . "\n";
}

// tap(random_int(400,1100), random_int(1630, 1799)); 广告

// tap(random_int(300,1200),random_int(1870, 2070));

start_app();

$rewards_time = time();
$dy_rewards_time = time();

while(1) {
    if ((time() - $rewards_time) >= 0) {
        sleep(2);
        rewards();

        $rewards_time = time() + 60*10;

        go_nav_bar(0);
        sleep(1);
        go_nav_bar(0);

        swipe_down();
    }

    if ((time() - $dy_rewards_time) >= 0) {
        dy_rewards();
        $dy_rewards_time = time() + 1200;
    }

    fake_read();

    # 随便点一下 进入第二级页面（也许没有）
    tap(random_int(350, 1100), random_int(1600, 2600));    
    fake_read();
    back();
}
