<table style="border-collapse: collapse;margin: 10px 0;font-family: monospace;">
    @foreach($response as $key=>$value)
        <tr>
            <td style="background: #000000;padding: 5px;border-bottom: 1px solid #666;">{{ $key }} </td>
            <td style="width: 85%;word-break: break-word;background: #ffdf00;color: #000;padding: 5px;border-bottom: 1px solid #afa140;">
                <div style="max-height:100px;overflow: auto">{{ $value }}</div>
            </td>
        </tr>
    @endforeach
</table>


