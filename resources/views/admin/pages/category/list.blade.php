@php
    use App\Helpers\Template as Template;
    use App\Helpers\Hightlight as Hightlight;

@endphp
<div class="x_content">
    <div class="table-responsive">
        <table class="table table-striped jambo_table bulk_action">
            <thead>
                <tr class="headings">
                    <th class="column-title">#</th>
                    <th class="column-title">Name</th>
                    <th class="column-title"></th>
                    <th class="column-title">Trạng thái</th>
                    <th class="column-title">Hiện thị Home</th>
                    <th class="column-title">Kiểu hiện thị</th>
                    <th class="column-title">Chỉnh sửa</th>
                    <th class="column-title">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if (count($items) > 0)
                    @foreach ($items as $key => $val)
                        @php
                            $lvl = '';
                            $depth = $val['depth'];
                            if($val['parent_id'] != 1)
                            {
                                for($i=0; $i< $depth-1; $i++)
                                {
                                    $lvl .= ' |-------- ';
                                }
                            }
                            $depthStr = "<span class='btn btn-success btn-icon d-inline'>" . $depth . "</span>";
                            $index           = $key + 1;
                            $class           = ($index % 2 == 0) ? "even" : "odd";
                            $id              = $val['id'];
                            $name            = $lvl . $depthStr . Hightlight::show($val['name'], $params['search'], 'name');
                            $status          = Template::showItemStatus($controllerName, $id, $val['status']);
                            $ordering        = Template::showItemOrdering($controllerName, $val['ordering'], $id);
                            $isHome          = Template::showItemIsHome($controllerName, $id, $val['is_home']);
                            $display         = Template::showItemSelect($controllerName, $id, $val['display'], 'display');
                            $createdHistory  = Template::showItemHistory($val['created_by'], $val['created']);
                            $modifiedHistory = Template::showItemHistory($val['modified_by'], $val['modified']);
                            $listBtnAction   = Template::showButtonAction($controllerName, $id);
                            $linkUp = route('up-node', ['id' => $id] );
                            $linkDown = route('down-node', ['id' => $id] );
                        @endphp

                        <tr class="{{ $class }} pointer">
                            <td >{{ $index }}</td>
                            <td width="20%">{!! $name !!}</td>
                            <td width="10%">
                                <div class="row">
                                    @if($val->getPrevSiblings()->toArray())
                                        <button class="btn btn-primary">
                                            <a href="{{ $linkUp }}" style="color:white;">
                                                <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                            </a>
                                        </button>
                                    @endif
                                    @if($val->getNextSibling())
                                        <button class="btn btn-primary">
                                            <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                            <td width="15%">{!! $status !!}</td>
                            <td>{!! $isHome  !!}</td>
                            <td>{!! $display !!}</td>
                            <td class="modified-{{$val['id']}}">{!! $modifiedHistory !!}</td>
                            <td class="last">{!! $listBtnAction !!}</td>
                        </tr>
                    @endforeach
                @else
                    @include('admin.templates.list_empty', ['colspan' => 6])
                @endif
            </tbody>
        </table>
    </div>
</div>
