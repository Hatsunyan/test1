/**
 * Created by Hatsu on 23.10.2016.
 */
$(document).ready(function()
{
    function postParse(resp)
    {
        try
        {
            var data = JSON.parse(resp);
        }catch(e)
        {
            return resp;
        }
        if(typeof (data.errors) == 'object')
        {
            $.each(data.errors,function(i,val)
            {
                messagesShow(val);
            })
        }else
        {
            messagesShow(data.errors);
        }
        return false;
    }

    function messagesShow(text)
    {
        var $div = $('.pattern').clone();
        $div.removeClass('pattern');
        $div.text(text);
        $div.appendTo('.messages').show();
        setTimeout(function(){$div.remove()},10000);
    }

    $('#start-search').on('click',function()
    {
        var data = $('#form-search').serializeArray();
        $.post('/search',{'data':data},function(resp)
        {
            $('table tr:not(:first)').remove();
            if(postParse(resp))
            {
                $('table tr').first().after(resp)
            }
        });
    });
});
