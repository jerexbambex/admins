<div>
    <div class="content">
        <div class="card">
            <div style="padding: 0 0px 20px 0px;" class="card-body">
                <div class="px-3 shadow-sm d-flex flex-row justify-content-between">
                    <div class="">
                        <h5 class="card-title">
                            View Student's Clearance File
                        </h5>
                    </div>
                    <div class="mt-2 ">
                        <div class="col-md">
                            {{-- <button disabled style="cursor: not-allowed;" class="btn btn-dark" wire:click="downloadAll">Download All</button> --}}
                        </div>
                    </div>
                </div>
                @forelse ($files as $file)
                <div class="p-3 mx-2 mt-3 d-flex flex-row justify-content-between">
                    <div class="">
                        <h6>{{$file->docname}}</h6>
                    </div>
                    <div class="">
                        {{-- <button class="btn btn-secondary" wire:click="download('{{$file->doc}}', '{{$file->docname}}', '{{$file->student->matric_no}}')">
                            <i class="fa fa-download"></i> Download
                        </button> --}}
                        <a class="btn btn-dark" target="_blank" href="{{route('admission.student.view_file', ['file_ref_id'=>$file->id])}}"><i class="fa fa-paper-plane"></i> Open</a>
                    </div>
                </div>
                @empty

                <h6 colspan="10" class="text-center text-danger">
                    No record found!
                </h6>

                @endforelse
            </div>
        </div>
        <!-- <script>
            async function downloadFile(event) {
                alert("You will be routed to another where your File will be downloaded")
                console.log(event.target.id)
                const dataurl = event.target.id
                const response = await fetch(event.target.id, {
                    method: "GET",
                    headers: {},
                    credentials: 'include',
                });

                const buffer = await response.arrayBuffer();
                const url = await window.URL.createObjectURL(new Blob([buffer]));
                const link = document.createElement("a");
                link.href = url;
                link.target = "_blank";
                link.setAttribute("download", `image.png`);
                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
                delete link;
            }
        </script> -->
    </div>
</div>