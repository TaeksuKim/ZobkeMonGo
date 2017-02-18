+function ($) {
    'use strict';

    var maxId = 251;
    var monsters = {};
    var myMonsters = {};
    var IMAGE_PREFIX='https://sites.google.com/site/pimagesmall/normal/';
    var IMAGE_POSTFIX='.png';
    var filter = {
        owner : 'all',
        rare : true,
        normal : true,
        unfound : true,
    };

    function toggleHaveData(id) {
        $.post('/poke/json_have.php', {
            monsterId: id
        }).done(function (res) {
            if (myMonsters[id]) {
                myMonsters[id] = false;
                monsters[id].ownerCount = monsters[id].ownerCount - 1;
                if (monsters[id].ownerCount < 1) {
                    monsters[id].status.all = 'unfound';
                    monsters[id].status.other = 'unfound';
                    monsters[id].status.my = 'unfound';
                } else if (monsters[id].ownerCount == 1) {
                    monsters[id].status.all = 'rare';
                    monsters[id].status.other = 'rare';
                    monsters[id].status.my = 'unfound';
                } else {
                    monsters[id].status.all = 'normal';
                    monsters[id].status.other = 'normal';
                    monsters[id].status.my = 'unfound';
                }
            } else {
                myMonsters[id] = true;
                monsters[id].ownerCount = monsters[id].ownerCount + 1;
                if (monsters[id].ownerCount < 1) {
                    monsters[id].status.all = 'unfound';
                    monsters[id].status.other = 'unfound';
                    monsters[id].status.my = 'unfound';
                } else if (monsters[id].ownerCount == 1) {
                    monsters[id].status.all = 'rare';
                    monsters[id].status.other = 'unfound';
                    monsters[id].status.my = 'rare';
                } else {
                    monsters[id].status.all = 'normal';
                    monsters[id].status.other = 'normal';
                    monsters[id].status.my = 'normal';
                }
            }
            filterMonsters();
            drawOwner(id);
        }).fail(function (res) {
        });
    }

    function loadData() {
        $.get('/poke/json_list.php', {
        }).done(function (res) {
            var json = $.parseJSON(res);
            var ms = json.monsters;
            fillMonsters(ms);

            $.get('/poke/json_mylist.php', {
            }).done(function (res) {
                var json = $.parseJSON(res);
                var myMonsters = json.monsters;

                fillMyMonsters(myMonsters);
                fillMonsterStatus();
                fillMonsterCount(monsters, 'all');

                showAllMonsters(true);
                for (var id = 1; id <= maxId; id++) {
                    drawOwner(id);
                }
            }).fail(function (res) {
            });
        }).fail(function (res) {
        });
    }

    function showAllMonsters(redrawImage) {
        for (var id = 1; id <= maxId; id++) {
            drawMonster(id, redrawImage);
        }
    }

    function fillMonsters(ms) {
        $.each(ms, function(_, monster) {
            var monsterId = parseInt(monster.monster_id);
            monsters[monsterId] = {
                id : monsterId,
                number: monster.monster_number,
                name: monster.monster_name,
                imageName : monster.monster_image,
                ownerCount : parseInt(monster.owner_count),
                newItem : (monster.new_item === 'Y'),
                visible : true,
                status : {
                    my : 'unfound',
                    other : 'unfound',
                    all : 'unfound',
                }
            }
        });
    }

    function fillMyMonsters(ms) {
        $.each(ms, function(_, monsterId) {
            var monsterId = parseInt(monsterId);
            myMonsters[monsterId] = true;
        });
    }

    function fillMonsterCount(ms, type) {
        var rareCount = 0;
        var normalCount = 0;
        var unfoundCount = 0;
        $.each(ms, function(_, monster) {
            var monsterId = monster.id;
            if ((type == 'my' && monster.status.my == 'rare') ||
                (type == 'other' && monster.status.other == 'rare') ||
                (type == 'all' && monster.status.all == 'rare')
                ) {
                rareCount++;
            }
            if ((type == 'my' && monster.status.my == 'normal') ||
                (type == 'other' && monster.status.other == 'normal') ||
                (type == 'all' && monster.status.all == 'normal')
                ) {
                normalCount++;
            }
            if ((type == 'my' && monster.status.my == 'unfound') ||
                (type == 'other' && monster.status.other == 'unfound') ||
                (type == 'all' && monster.status.all == 'unfound')
                ) {
                unfoundCount++;
            }
        });
        $('#rare-count').text(rareCount);
        $('#normal-count').text(normalCount);
        $('#unfound-count').text(unfoundCount);
        $('#total-catch-count').text(rareCount + normalCount);
    }

    function drawMonster(id, redrawImage) {
        var $rootPanel = $('#monster-root-panel-' + id);
        if (!$rootPanel) return;

        var monster = monsters[id];
        if (!monster) {
            $rootPanel.css('display', 'none');
            return;
        }

        if (!monster.visible) {
            $rootPanel.css('display', 'none');
            return;
        }

        $rootPanel.css('display', 'block');
        var number = '???'
        var name = '??????';
        var faceImage = '';
        if (monster) {
            number = monster.number;
            name = monster.name;
            faceImage = IMAGE_PREFIX + monster.imageName + IMAGE_POSTFIX;
        }

        $rootPanel.find('.monster-number').text(number);
        $rootPanel.find('.monster-name').text(name);
        if (redrawImage)
            $rootPanel.find('.monster-face-image').attr('src', faceImage);
    }

    function drawOwner(id) {
        var $rootPanel = $('#monster-root-panel-' + id);
        if (!$rootPanel) return;
        if (!monsters[id]) return;

        var ownerCount = 0;
        if (monsters[id]) {
            ownerCount = monsters[id].ownerCount;
        }
        var isMyMonster = false;
        if (myMonsters[id]) {
            isMyMonster = true;
        }
        var newItem = false;
        if (monsters[id]) {
            newItem = monsters[id].newItem;
        }
        
        var $ownerBadge = $rootPanel.find('.monster-owner-badge');
        if ($ownerBadge) {
            if (ownerCount < 1) {
                $ownerBadge.text('미발견');
                $ownerBadge.removeClass('badge-default');
                $ownerBadge.addClass('badge-danger');
                $ownerBadge.removeClass('badge-primary');
            } else if (ownerCount > 1) {
                $ownerBadge.text('잡(' + ownerCount + ')');
                $ownerBadge.addClass('badge-default');
                $ownerBadge.removeClass('badge-danger');
                $ownerBadge.removeClass('badge-primary');
            } else {
                $ownerBadge.text('레어');
                $ownerBadge.removeClass('badge-default');
                $ownerBadge.removeClass('badge-danger');
                $ownerBadge.addClass('badge-primary');
            }
        }

        var $newItemBadge = $rootPanel.find('.monster-owner-new-badge');
        if ($newItemBadge) {
            if (newItem) {
                $newItemBadge.css('display', 'inline');
            } else {
                $newItemBadge.css('display', 'none');
            }
        }

        var $uniqueOwnerName = $rootPanel.find('.unique-owner-name');
        if ($uniqueOwnerName) {
            if (ownerCount < 1) {
                $uniqueOwnerName.text('미발견');
                $uniqueOwnerName.parent().removeClass('active');
            } else if (ownerCount > 1) {
                $uniqueOwnerName.text('잡(' + ownerCount + ')');
                $uniqueOwnerName.parent().removeClass('active');
            } else {
                $uniqueOwnerName.text('레어');
                $uniqueOwnerName.parent().addClass('acive');
            }
        }

        var $ownerMyBadge = $rootPanel.find('.monster-owner-my-badge');
        if ($ownerMyBadge) {
            if (isMyMonster) {
                $ownerMyBadge.css('display', 'inline');
            } else {
                $ownerMyBadge.css('display', 'none');
            }
        }

        var $facePanel = $rootPanel.find('.monster-face-panel');
        var $ownerButton = $rootPanel.find('.owner-button');
        if ($facePanel) {
            if (ownerCount < 1) {
                $facePanel.removeClass('ordinary');
                $facePanel.removeClass('unique');
                $facePanel.addClass('none');
                if ($ownerButton) {
                    $ownerButton.css('visibility', 'hidden');
                }
            } else if (ownerCount > 1) {
                $facePanel.addClass('ordinary');
                $facePanel.removeClass('unique');
                $facePanel.removeClass('none');
                if ($ownerButton) {
                    $ownerButton.css('visibility', 'visible');
                }
            } else {
                $facePanel.removeClass('ordinary');
                $facePanel.addClass('unique');
                $facePanel.removeClass('none');
                if ($ownerButton) {
                    $ownerButton.css('visibility', 'visible');
                }
            }
        }
        var $faceImage = $rootPanel.find('.monster-face-image');
        if ($faceImage) {
            if (ownerCount < 1) {
                $faceImage.removeClass('ordinary');
                $faceImage.removeClass('unique');
                $faceImage.addClass('none');
            } else if (ownerCount > 1) {
                $faceImage.addClass('ordinary');
                $faceImage.removeClass('unique');
                $faceImage.removeClass('none');
            } else {
                $faceImage.removeClass('ordinary');
                $faceImage.addClass('unique');
                $faceImage.removeClass('none');
            }
        }

        var $haveButton = $rootPanel.find('#have-button');
        if (isMyMonster) {
            $haveButton.text('없어요');
            $haveButton.removeClass('btn-danger');
            $haveButton.addClass('btn-warning');
        } else {
            $haveButton.text('있어요');
            $haveButton.addClass('btn-danger');
            $haveButton.removeClass('btn-warning');
        }
    }

    function findRootPanel($obj) {
        var fd = function (depth, $obj) {
            if (depth > 10) return undefined;
            if (!$obj) return undefined;

            var number = $obj.attr('data-monster-number');
            if (number) {
                return { 
                    $obj : $obj,
                    number: number,
                };
            }

            return fd(depth + 1, $obj.parent());
        };
        return fd(0, $obj);
    }

    function toggleDetail($obj) {
        var rootPanel = findRootPanel($obj);
        if (!rootPanel) return;

        var detailPane = rootPanel.$obj.find('.have-button');
        if (!detailPane) return;

        var isActive = detailPane.hasClass('visible');

        $.each($('.have-button'), function(_, panel) {
            var $panel = $(panel);
            if ($panel.hasClass('visible'))
                $panel.removeClass('visible');
        });
        if (isActive) {
            detailPane.removeClass('visible');
        } else {
            detailPane.addClass('visible');
        }
    }

    function setOwnStatus($obj) {
        var rootPanel = findRootPanel($obj);
        if (!rootPanel) return;
        toggleHaveData(rootPanel.number);
    }

    function fillMonsterStatus() {
        $.each(monsters, function(_, monster) {
            var monsterId = monster.id;
            if (monster.ownerCount < 1) {
                monster.status = {
                    my : 'unfound',
                    other : 'unfound',
                    all : 'unfound',
                };
            } else if (monster.ownerCount == 1) {
                var isMyMonster = myMonsters[monsterId];
                monster.status = {
                    my : (isMyMonster ? 'rare' : 'unfound'),
                    other : (!isMyMonster ? 'rare' : 'unfound'),
                    all : 'rare',
                };
            } else if (monster.ownerCount > 1) {
                var isMyMonster = myMonsters[monsterId];
                monster.status = {
                    my : (isMyMonster ? 'normal' : 'unfound'),
                    other : 'normal',
                    all : 'normal',
                };
            }
        });
    }

    function filterMonsters() {
        var countType = 'all';
        if ($('#owner-tab-my').hasClass('active')) {
            filter.my = true;
            filter.other = false;
            countType = 'my';
        } else if ($('#owner-tab-other').hasClass('active')) {
            filter.my = false;
            filter.other = true;
            countType = 'other';
        } else {
            filter.my = true;
            filter.other = true;
            countType = 'all';
        }
        filter.rare = $('#filter-rare-checkbox').is(':checked');
        filter.normal = $('#filter-normal-checkbox').is(':checked');
        filter.unfound = $('#filter-unfound-checkbox').is(':checked');

        $.each(monsters, function(_, monster) {
            var monsterId = monster.id;
            monster.visible = false;
            var category = 'unknown';
            if (filter.my && filter.other) {
                category = monster.status.all;
            } else if (filter.my) {
                category = monster.status.my;
            } else if (filter.other) {
                category = monster.status.other;
            }
            if (category == 'unfound' && filter.unfound) {
                monster.visible = true;
            }
            if (category == 'rare' && filter.rare) {
                monster.visible = true;
            }
            if (category == 'normal' && filter.normal) {
                monster.visible = true;
            }
        });
        fillMonsterCount(monsters, countType);
        showAllMonsters(false);
    }

    function showOwners($obj) {
        var rootPanel = findRootPanel($obj);
        if (!rootPanel) return;
        var monsterId = rootPanel.number;
        $.get('/poke/json_owner_list.php', {
            monsterId: monsterId,
        }).done(function (res) {
            var json = $.parseJSON(res);
            var owners = json.owners;
            if (owners && owners.length > 0) {
                var text = "";
                $.each(owners, function(index, owner) {
                    if (index > 0) text += ", ";
                    text += owner.user_name;
                });
                $('#ownerListText').text(text);
                $('#ownerModal').modal();
            }
        });
    }

    function addListeners() {
        $.each($('.monster-detail-header'), function(_,item) {
            $(item).on('click', function(e) {
                toggleDetail($(e.target));
            });
        });
        $.each($('.monster-detail-panel'), function(_,item) {
            $(item).on('click', function(e) {
                toggleDetail($(e.target));
            });
        });
        $.each($('.owner-tab-item'), function(_, item) {
            var $item = $(item);
            $item.off('click').on('click', function(event) {
                $.each($('.owner-tab-item'), function(_, i) {
                    $(i).removeClass('active');
                });
                $item.addClass('active');
                filterMonsters();
            });
        });
        $.each($('.filter-category-checkbox'), function(_, checkbox) {
            $(checkbox).off('click').on('click', function(e) {
                filterMonsters();
            });
        });
        $.each($('.have-button'), function(_,item) {
            $(item).on('click', function(e) {
                setOwnStatus($(e.target));
            });
        });
        $.each($('.owner-button'), function(_,item) {
            $(item).on('click', function(e) {
                showOwners($(e.target));
                e.stopPropagation();
            });
        });
    }

    $(window).on('load', function () {
        addListeners();
        loadData();
    })

}(jQuery);
